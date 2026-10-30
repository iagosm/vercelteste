<?php

use App\Enums\StatusPedido;

beforeEach(function () {
    $this->withoutVite();
});

test('panel pages render', function (string $rota, array $parametros, string $texto) {
    $this->get(route($rota, $parametros))->assertOk()->assertSee($texto);
})->with([
    'login' => ['painel.entrar', [], 'Entrar no painel'],
    'visão geral' => ['painel.dashboard', [], 'Precisam de atenção'],
    'pedidos' => ['painel.pedidos.index', [], 'Registrar pedido'],
    'pedido' => ['painel.pedidos.show', ['pedido' => 1042], 'Andamento'],
    'produtos' => ['painel.produtos.index', [], 'Cadastrar produto'],
    'novo produto' => ['painel.produtos.create', [], 'Variações e estoque'],
    'editar produto' => ['painel.produtos.edit', ['produto' => 'iphone-17-pro'], 'Remover produto'],
    'clientes' => ['painel.clientes.index', [], 'Marina Costa'],
    'cliente' => ['painel.clientes.show', ['cliente' => 1], 'Histórico de atendimento'],
    'categorias' => ['painel.categorias.index', [], 'Adicionar categoria'],
    'configurações' => ['painel.configuracoes.edit', [], 'Estados do pedido'],
]);

test('unknown records return not found', function (string $rota, array $parametros) {
    $this->get(route($rota, $parametros))->assertNotFound();
})->with([
    ['painel.pedidos.show', ['pedido' => 9999]],
    ['painel.produtos.edit', ['produto' => 'nao-existe']],
    ['painel.clientes.show', ['cliente' => 9999]],
]);

test('orders can be filtered by status', function () {
    $response = $this->get(route('painel.pedidos.index', ['status' => StatusPedido::Novo->value]));

    $response->assertViewHas('pedidos', fn ($pedidos) => $pedidos->pluck('id')->all() === [1042, 1041]);
});

test('orders can be searched by customer', function () {
    $response = $this->get(route('painel.pedidos.index', ['q' => 'patricia']));

    $response->assertViewHas('pedidos', fn ($pedidos) => $pedidos->pluck('id')->all() === [1035]);
});

test('staff can sign in and out', function () {
    $this->post(route('painel.entrar.store'), ['email' => 'gustavo@loja.com', 'password' => 'segredo'])
        ->assertRedirect(route('painel.dashboard'))
        ->assertSessionHas('painel_usuario', 'Gustavo');

    $this->post(route('painel.sair'))
        ->assertRedirect(route('painel.entrar'))
        ->assertSessionMissing('painel_usuario');
});

test('staff login validates the credentials', function () {
    $this->post(route('painel.entrar.store'), ['email' => '', 'password' => ''])
        ->assertSessionHasErrors(['email', 'password']);
});

test('order status change is validated and acknowledged', function () {
    $this->from(route('painel.pedidos.show', 1042))
        ->patch(route('painel.pedidos.update', 1042), ['status' => StatusPedido::EmAtendimento->value])
        ->assertRedirect(route('painel.pedidos.show', 1042))
        ->assertSessionHas('status');

    $this->patch(route('painel.pedidos.update', 1042), ['status' => 'invalido'])
        ->assertSessionHasErrors('status');
});

test('registering an order validates the form', function () {
    $this->post(route('painel.pedidos.store'), [
        'cliente_id' => 1,
        'produto' => 'iphone-17-pro',
        'valor' => 8490,
        'origem' => 'Site → WhatsApp',
    ])->assertRedirect(route('painel.pedidos.index'))->assertSessionHas('status');

    $this->post(route('painel.pedidos.store'), ['cliente_id' => 999, 'produto' => 'x', 'valor' => -1, 'origem' => 'Outro'])
        ->assertSessionHasErrors(['cliente_id', 'produto', 'valor', 'origem']);
});

test('product form validates required fields', function () {
    $this->post(route('painel.produtos.store'), [])
        ->assertSessionHasErrors(['nome', 'categoria', 'condicao', 'preco', 'disponibilidade']);

    $this->put(route('painel.produtos.update', 'iphone-17-pro'), [
        'nome' => 'iPhone 17 Pro',
        'categoria' => 'iphone',
        'condicao' => 'novo',
        'preco' => 8490,
        'disponibilidade' => 'em_estoque',
        'ativo' => 1,
        'variacoes' => [['cor' => 'Azul', 'opcao' => '256 GB', 'adicional' => 0, 'estoque' => 3]],
    ])->assertRedirect(route('painel.produtos.index'))->assertSessionHas('status');
});

test('categories with products cannot be removed', function () {
    $this->delete(route('painel.categorias.destroy', 'iphone'))
        ->assertSessionHas('status', 'Mova ou remova os produtos antes.');
});
