<?php

beforeEach(function () {
    $this->withoutVite();
});

test('home shows categories and highlights', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Por onde você quer começar?');
    $response->assertSee('Apple Watch');
    $response->assertSee('iPhone 17 Pro');
});

test('catalog lists only active products', function () {
    $response = $this->get(route('catalogo'));

    $response->assertOk();
    $response->assertSee('Catálogo completo');
    $response->assertViewHas('produtos', fn ($produtos) => $produtos->contains('slug', 'iphone-18-pro')
        && ! $produtos->contains('slug', 'capa-de-silicone-iphone'));
});

test('catalog filters by category and condition', function () {
    $response = $this->get(route('catalogo', ['categoria' => 'iphone', 'condicao' => ['seminovo']]));

    $response->assertOk();
    $response->assertSee('iPhone');
    $response->assertViewHas('produtos', fn ($produtos) => $produtos->pluck('slug')->all() === ['iphone-14', 'iphone-13']);
});

test('catalog sorts by lowest price', function () {
    $response = $this->get(route('catalogo', ['categoria' => 'ipad', 'ordem' => 'menor']));

    $response->assertViewHas('produtos', fn ($produtos) => $produtos->pluck('preco')->all() === [1790, 2990, 5290, 9490]);
});

test('catalog search shows a message when nothing matches', function () {
    $response = $this->get(route('catalogo', ['q' => 'nokia']));

    $response->assertOk();
    $response->assertSee('Resultados');
    $response->assertSee('Não achamos esse aparelho com esses filtros.');
});

test('product page shows details and specifications', function () {
    $response = $this->get(route('produtos.show', 'iphone-17-pro'));

    $response->assertOk();
    $response->assertSee('iPhone 17 Pro');
    $response->assertSee('Especificações');
    $response->assertSee('Comprar pelo WhatsApp');
});

test('unknown and inactive products return not found', function (string $slug) {
    $this->get(route('produtos.show', $slug))->assertNotFound();
})->with(['nao-existe', 'capa-de-silicone-iphone']);

test('about and contact pages render', function (string $rota, string $texto) {
    $this->get(route($rota))->assertOk()->assertSee($texto);
})->with([
    ['sobre', 'Como a gente trabalha'],
    ['contato', 'Perguntas frequentes'],
]);

test('account redirects visitors to the login page', function () {
    $this->get(route('cliente.conta'))->assertRedirect(route('cliente.entrar'));
});

test('login requires a valid email and password', function () {
    $this->post(route('cliente.entrar.store'), ['email' => 'invalido', 'password' => '12'])
        ->assertSessionHasErrors(['email', 'password']);
});

test('customer can sign in, see the account and sign out', function () {
    $this->post(route('cliente.entrar.store'), ['email' => 'joao.silva@exemplo.com', 'password' => 'segredo'])
        ->assertRedirect(route('cliente.conta'));

    $this->get(route('cliente.conta'))
        ->assertOk()
        ->assertSee('Olá, Joao.')
        ->assertSee('Meus pedidos');

    $this->post(route('cliente.sair'))->assertRedirect(route('home'));
    $this->get(route('cliente.conta'))->assertRedirect(route('cliente.entrar'));
});

test('customer can create an account', function () {
    $this->get(route('cliente.cadastro'))->assertOk()->assertSee('Criar sua conta.');

    $this->post(route('cliente.cadastro.store'), ['name' => 'Ana Paula', 'email' => 'ana@exemplo.com', 'password' => 'segredo'])
        ->assertRedirect(route('cliente.conta'));

    $this->get(route('cliente.conta'))->assertSee('Olá, Ana.');
});
