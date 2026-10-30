<?php

namespace App\Http\Controllers\Painel;

use App\Enums\StatusPedido;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracaoController extends Controller
{
    public function edit(): View
    {
        return view('painel.configuracoes.edit', [
            'configuracao' => [
                'nome' => config('loja.nome'),
                'whatsapp' => config('loja.whatsapp') === '00000000000' ? '' : config('loja.whatsapp'),
                'horario' => str_starts_with(config('loja.horario'), '[') ? '' : config('loja.horario'),
            ],
            'status' => StatusPedido::cases(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'regex:/^[\d\s()+-]{10,20}$/'],
            'horario' => ['nullable', 'string', 'max:255'],
        ]);

        return back()->with('status', 'Configurações recebidas. Nada foi salvo.');
    }
}
