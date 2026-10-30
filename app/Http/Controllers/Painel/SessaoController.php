<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Login da equipe.
 *
 * Provisório: qualquer e-mail e senha válidos entram. O nome exibido no
 * painel fica na sessão até existir a tabela de usuários da equipe.
 */
class SessaoController extends Controller
{
    public function create(): View
    {
        return view('painel.entrar');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $nome = str($dados['email'])->before('@')->replace(['.', '_', '-'], ' ')->title()->toString();

        $request->session()->regenerate();
        $request->session()->put('painel_usuario', $nome);

        return to_route('painel.dashboard')->with('status', "Você entrou como {$nome}.");
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('painel_usuario');
        $request->session()->regenerateToken();

        return to_route('painel.entrar');
    }
}
