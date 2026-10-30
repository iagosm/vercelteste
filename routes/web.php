<?php

use App\Http\Controllers\Loja;
use App\Http\Controllers\Painel;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\ClienteAutenticado;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Loja (o que o cliente vê)
|--------------------------------------------------------------------------
*/

Route::get('/', Loja\HomeController::class)->name('home');
Route::get('/catalogo', Loja\CatalogoController::class)->name('catalogo');
Route::get('/produto/{slug}', [Loja\ProdutoController::class, 'show'])->name('produtos.show');
Route::get('/sobre', Loja\SobreController::class)->name('sobre');
Route::get('/contato', Loja\ContatoController::class)->name('contato');

Route::name('cliente.')->group(function () {
    Route::get('/entrar', [Loja\SessaoController::class, 'create'])->name('entrar');
    Route::post('/entrar', [Loja\SessaoController::class, 'store'])->name('entrar.store');
    Route::post('/sair', [Loja\SessaoController::class, 'destroy'])->name('sair');

    Route::get('/criar-conta', [Loja\CadastroController::class, 'create'])->name('cadastro');
    Route::post('/criar-conta', [Loja\CadastroController::class, 'store'])->name('cadastro.store');

    Route::middleware(ClienteAutenticado::class)->prefix('conta')->group(function () {
        Route::get('/', [Loja\ContaController::class, 'show'])->name('conta');

        Route::get('/pedidos', [Loja\PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/por-data', Loja\PedidoPorDataController::class)->name('pedidos.por_data');
        Route::get('/pedidos/{pedido}', [Loja\PedidoController::class, 'show'])->whereNumber('pedido')->name('pedidos.show');

        Route::get('/perfil', [Loja\PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/perfil', [Loja\PerfilController::class, 'update'])->name('perfil.update');
        Route::put('/senha', [Loja\SenhaController::class, 'update'])->name('senha.update');
    });
});

/*
|--------------------------------------------------------------------------
| Painel administrativo (equipe da loja)
|--------------------------------------------------------------------------
*/

Route::prefix('painel')->name('painel.')->group(function () {
    Route::get('/entrar', [Painel\SessaoController::class, 'create'])->name('entrar');
    Route::post('/entrar', [Painel\SessaoController::class, 'store'])->name('entrar.store');
    Route::post('/sair', [Painel\SessaoController::class, 'destroy'])->name('sair');

    Route::get('/', Painel\DashboardController::class)->name('dashboard');

    Route::resource('pedidos', Painel\PedidoController::class)->only(['index', 'store', 'show', 'update']);
    Route::resource('produtos', Painel\ProdutoController::class)->except(['show']);
    Route::resource('clientes', Painel\ClienteController::class)->only(['index', 'show']);
    Route::post('/clientes/{cliente}/anotacoes', [Painel\ClienteAnotacaoController::class, 'store'])->name('clientes.anotacoes.store');
    Route::resource('categorias', Painel\CategoriaController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/configuracoes', [Painel\ConfiguracaoController::class, 'edit'])->name('configuracoes.edit');
    Route::put('/configuracoes', [Painel\ConfiguracaoController::class, 'update'])->name('configuracoes.update');
});

/*
|--------------------------------------------------------------------------
| Breeze (padrão do starter kit)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
