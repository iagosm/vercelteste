<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Dados pessoais e endereço de entrega do cliente.
 *
 * Provisório: os dados ficam na sessão do login fictício. Quando existir a
 * tabela de clientes, salve no model em vez da sessão.
 */
class PerfilController extends Controller
{
    public const ESTADOS = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

    public function edit(Request $request): View
    {
        return view('loja.conta.perfil', [
            'cliente' => $request->session()->get('cliente') + [
                'telefone' => '',
                'endereco' => [],
            ],
            'estados' => self::ESTADOS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'regex:/^[\d\s()+-]{10,20}$/'],
            'endereco.cep' => ['nullable', 'string', 'regex:/^\d{5}-?\d{3}$/'],
            'endereco.rua' => ['nullable', 'string', 'max:255'],
            'endereco.numero' => ['nullable', 'string', 'max:20'],
            'endereco.complemento' => ['nullable', 'string', 'max:100'],
            'endereco.bairro' => ['nullable', 'string', 'max:100'],
            'endereco.cidade' => ['nullable', 'string', 'max:100'],
            'endereco.estado' => ['nullable', 'in:'.implode(',', self::ESTADOS)],
        ], [
            'telefone.regex' => 'Digite o telefone com DDD, só números.',
            'endereco.cep.regex' => 'Digite um CEP válido, como 01310-100.',
        ]);

        $request->session()->put('cliente', [
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'] ?? '',
            'endereco' => array_filter($dados['endereco'] ?? []),
        ]);

        return to_route('cliente.perfil.edit')->with('status', 'Dados atualizados só nesta sessão. Nada foi salvo no banco.');
    }
}
