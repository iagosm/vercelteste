<?php

namespace App\Http\Controllers\Painel;

use App\Enums\StatusPedido;
use App\Http\Controllers\Controller;
use App\Support\DemoData;
use App\Support\WhatsApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $nomes = DemoData::clientes()->pluck('nome', 'id');
        $status = StatusPedido::tryFrom($request->string('status')->toString());
        $busca = Str::of($request->string('q')->trim())->ascii()->lower()->toString();

        $pedidos = DemoData::pedidos()
            ->map(fn (array $p): array => $p + ['cliente' => $nomes[$p['cliente_id']]])
            ->when($status, fn ($pedidos) => $pedidos->where('status', $status))
            ->when($busca !== '', fn ($pedidos) => $pedidos->filter(
                fn (array $p): bool => str_contains(Str::of("#{$p['id']} {$p['cliente']} {$p['produto']}")->ascii()->lower()->toString(), $busca)
            ))
            ->values();

        return view('painel.pedidos.index', [
            'pedidos' => $pedidos,
            'statusAtual' => $status,
            'contagem' => DemoData::pedidos()->countBy(fn (array $p): string => $p['status']->value),
            'total' => DemoData::pedidos()->count(),
            'clientes' => DemoData::clientes(),
            'produtos' => DemoData::produtos(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cliente_id' => ['required', 'integer', Rule::in(DemoData::clientes()->pluck('id'))],
            'produto' => ['required', 'string', Rule::in(DemoData::produtos()->pluck('slug'))],
            'configuracao' => ['nullable', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'origem' => ['required', Rule::in(DemoData::ORIGENS)],
        ]);

        $numero = DemoData::pedidos()->max('id') + 1;

        return to_route('painel.pedidos.index')->with('status', "Pedido #{$numero} registrado como Novo. Nada foi salvo.");
    }

    public function show(string $pedido): View
    {
        $pedido = DemoData::pedido((int) $pedido);

        abort_if($pedido === null, 404);

        $cliente = DemoData::cliente($pedido['cliente_id']);

        return view('painel.pedidos.show', [
            'pedido' => $pedido,
            'cliente' => $cliente,
            'linkWhatsApp' => WhatsApp::link("Olá! Aqui é o Gustavo, sobre o seu pedido #{$pedido['id']}.", $cliente['telefone']),
        ]);
    }

    /**
     * Muda o status ou salva a observação interna.
     */
    public function update(Request $request, string $pedido): RedirectResponse
    {
        abort_if(DemoData::pedido((int) $pedido) === null, 404);

        $dados = $request->validate([
            'status' => ['sometimes', Rule::enum(StatusPedido::class)],
            'observacao' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ]);

        $mensagem = isset($dados['status'])
            ? 'Status alterado para '.StatusPedido::from($dados['status'])->label().'. Nada foi salvo.'
            : 'Observação recebida. Nada foi salvo.';

        return back()->with('status', $mensagem);
    }
}
