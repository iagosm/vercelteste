<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use App\Support\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $busca = Str::of($request->string('q')->trim())->ascii()->lower()->toString();

        $clientes = DemoData::clientes()
            ->when($busca !== '', fn ($clientes) => $clientes->filter(
                fn (array $c): bool => str_contains(Str::of("{$c['nome']} {$c['telefone']}")->ascii()->lower()->toString(), $busca)
            ))
            ->map(function (array $cliente): array {
                $pedidos = DemoData::pedidosDoCliente($cliente['id']);

                return $cliente + [
                    'total_pedidos' => $pedidos->count(),
                    'ultimo_pedido' => $pedidos->first()['registrado_em'] ?? null,
                ];
            })
            ->values();

        return view('painel.clientes.index', ['clientes' => $clientes]);
    }

    public function show(string $cliente): View
    {
        $cliente = DemoData::cliente((int) $cliente);

        abort_if($cliente === null, 404);

        return view('painel.clientes.show', [
            'cliente' => $cliente,
            'pedidos' => DemoData::pedidosDoCliente($cliente['id']),
            'linkWhatsApp' => WhatsApp::link('Olá, '.Str::before($cliente['nome'], ' ').'! Aqui é o Gustavo.', $cliente['telefone']),
        ]);
    }
}
