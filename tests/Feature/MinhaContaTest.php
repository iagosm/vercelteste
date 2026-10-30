<?php

use App\Enums\StatusPedido;

beforeEach(function () {
    $this->withoutVite();
});

function entrarComoCliente(): void
{
    test()->withSession(['cliente' => ['nome' => 'Joao Silva', 'email' => 'joao@exemplo.com']]);
}

test('account pages require a signed in customer', function (string $rota, array $parametros) {
    $this->get(route($rota, $parametros))->assertRedirect(route('cliente.entrar'));
})->with([
    ['cliente.conta', []],
    ['cliente.pedidos.index', []],
    ['cliente.pedidos.show', ['pedido' => 2006]],
    ['cliente.pedidos.por_data', []],
    ['cliente.perfil.edit', []],
]);

test('header shows an account menu with sign out for signed in customers', function () {
    entrarComoCliente();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Menu da conta de Joao Silva')
        ->assertSee(route('cliente.pedidos.por_data'))
        ->assertSee(route('cliente.perfil.edit'))
        ->assertSee('action="'.route('cliente.sair').'"', false);
});

test('visitors see the sign in link instead of the account menu', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Menu da conta')
        ->assertSee(route('cliente.entrar'));
});

test('footer shows whatsapp, instagram and email contacts', function () {
    config(['loja.instagram' => '@gustavoapple', 'loja.email' => 'contato@gustavo.com']);

    $this->get(route('home'))
        ->assertSee('Fale com a gente')
        ->assertSee('https://www.instagram.com/gustavoapple')
        ->assertSee('mailto:contato@gustavo.com')
        ->assertSee('Falar pelo WhatsApp');
});

test('account summary shows recent orders and profile shortcuts', function () {
    entrarComoCliente();

    $this->get(route('cliente.conta'))
        ->assertOk()
        ->assertSee('Olá, Joao.')
        ->assertSee('Últimos pedidos')
        ->assertSee('Editar perfil')
        ->assertViewHas('recentes', fn ($recentes) => $recentes->pluck('id')->all() === [2006, 2005, 2004]);
});

test('orders list can be filtered by situation', function (?string $situacao, array $ids) {
    entrarComoCliente();

    $this->get(route('cliente.pedidos.index', array_filter(['situacao' => $situacao])))
        ->assertOk()
        ->assertViewHas('pedidos', fn ($pedidos) => $pedidos->pluck('id')->all() === $ids);
})->with([
    'todos' => [null, [2006, 2005, 2004, 2003, 2002, 2001, 1999, 1998, 1990]],
    'em andamento' => ['andamento', [2006, 2005, 2004, 2003]],
    'concluídos' => ['concluidos', [2002, 1999, 1998, 1990]],
    'cancelados' => ['cancelados', [2001]],
    'situação inválida mostra todos' => ['xyz', [2006, 2005, 2004, 2003, 2002, 2001, 1999, 1998, 1990]],
]);

test('order detail shows progress, payment and delivery', function () {
    entrarComoCliente();

    $this->get(route('cliente.pedidos.show', 2004))
        ->assertOk()
        ->assertSee('Pedido #2004')
        ->assertSee(StatusPedido::AguardandoConfirmacao->descricaoCliente())
        ->assertSee('Cartão em 10x')
        ->assertSee('Entrega por motoboy')
        ->assertSee('Falar sobre este pedido');
});

test('unknown order returns not found', function () {
    entrarComoCliente();

    $this->get(route('cliente.pedidos.show', 9999))->assertNotFound();
});

test('orders by date are grouped by day, newest first', function () {
    entrarComoCliente();

    $response = $this->get(route('cliente.pedidos.por_data'));

    $response->assertOk();
    $response->assertViewHas('quantidade', 9);
    $response->assertViewHas('dias', function ($dias) {
        $datas = $dias->map(fn ($dia) => $dia['data']->toDateString())->all();

        return $datas === collect($datas)->sortDesc()->values()->all()
            && $dias->contains(fn ($dia) => $dia['pedidos']->pluck('id')->sort()->values()->all() === [1998, 1999]);
    });
});

test('orders by date filters with shortcuts and custom ranges', function () {
    entrarComoCliente();

    $this->get(route('cliente.pedidos.por_data', ['periodo' => '7-dias']))
        ->assertViewHas('quantidade', 3)
        ->assertViewHas('valorTotal', 8490 + 1990 + 2390);

    $this->get(route('cliente.pedidos.por_data', ['de' => now()->subDays(20)->toDateString(), 'ate' => now()->toDateString()]))
        ->assertViewHas('quantidade', 5)
        ->assertViewHas('atalhoAtual', null);
});

test('orders by date rejects an end date before the start date', function () {
    entrarComoCliente();

    $this->get(route('cliente.pedidos.por_data', ['de' => '2026-10-20', 'ate' => '2026-10-01']))
        ->assertSessionHasErrors('ate');
});

test('customer can update the profile', function () {
    entrarComoCliente();

    $this->get(route('cliente.perfil.edit'))->assertOk()->assertSee('Endereço de entrega');

    $this->put(route('cliente.perfil.update'), [
        'nome' => 'Joana Lima',
        'email' => 'joana@exemplo.com',
        'telefone' => '(11) 99999-9999',
        'endereco' => ['cep' => '01310-100', 'cidade' => 'São Paulo', 'estado' => 'SP'],
    ])->assertRedirect(route('cliente.perfil.edit'))
        ->assertSessionHas('cliente.nome', 'Joana Lima')
        ->assertSessionHas('cliente.endereco.cidade', 'São Paulo');

    $this->get(route('cliente.conta'))->assertSee('Olá, Joana.');
});

test('profile update validates the fields', function () {
    entrarComoCliente();

    $this->put(route('cliente.perfil.update'), [
        'nome' => '',
        'email' => 'invalido',
        'telefone' => 'abc',
        'endereco' => ['cep' => '123', 'estado' => 'XX'],
    ])->assertSessionHasErrors(['nome', 'email', 'telefone', 'endereco.cep', 'endereco.estado']);
});

test('password change requires confirmation and minimum length', function () {
    entrarComoCliente();

    $this->put(route('cliente.senha.update'), ['senha_atual' => 'antiga', 'password' => '123', 'password_confirmation' => '321'])
        ->assertSessionHasErrorsIn('senha', ['password']);

    $this->put(route('cliente.senha.update'), ['senha_atual' => 'antiga', 'password' => 'novaSenha123', 'password_confirmation' => 'novaSenha123'])
        ->assertRedirect(route('cliente.perfil.edit'))
        ->assertSessionHas('status');
});
