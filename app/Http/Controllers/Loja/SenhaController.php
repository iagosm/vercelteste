<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

/**
 * Troca de senha do cliente.
 *
 * Provisório: valida o formulário, mas não confere a senha atual porque o
 * login ainda não usa banco. Use a regra `current_password` quando usar.
 */
class SenhaController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validateWithBag('senha', [
            'senha_atual' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        return to_route('cliente.perfil.edit')->with('status', 'Senha validada. Nada foi salvo no banco.');
    }
}
