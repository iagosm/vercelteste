<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Login do cliente.
 *
 * Provisório: não consulta o banco. Guarda nome e e-mail na sessão só para
 * as telas mostrarem o estado "logado".
 */
class SessaoController extends Controller
{
    public function create(): View
    {
        return view('loja.conta.entrar', ['cadastro' => false]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $nome = str($dados['email'])->before('@')->replace(['.', '_', '-'], ' ')->title()->toString();

        $request->session()->regenerate();
        $request->session()->put('cliente', ['nome' => $nome, 'email' => $dados['email']]);

        return to_route('cliente.conta');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cliente');
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
