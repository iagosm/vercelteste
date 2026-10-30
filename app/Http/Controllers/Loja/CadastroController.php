<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Cadastro do cliente.
 *
 * Provisório: não cria registro no banco, só guarda os dados na sessão.
 */
class CadastroController extends Controller
{
    public function create(): View
    {
        return view('loja.conta.entrar', ['cadastro' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $request->session()->regenerate();
        $request->session()->put('cliente', ['nome' => $dados['name'], 'email' => $dados['email']]);

        return to_route('cliente.conta');
    }
}
