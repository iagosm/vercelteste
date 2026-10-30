<?php

namespace App\Http\Controllers\Loja;

use App\Enums\StatusPedido;
use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pedidos do cliente, registrados pelo vendedor depois da conversa no WhatsApp.
 */
class PedidoController extends Controller
{
    /**
     * Filtros simples para o cliente: ele não precisa ver todas as etapas internas.
     *
     * @var array<string, string>
     */
    private const SITUACOES = [
        'andamento' => 'Em andamento',
        'concluidos' => 'Concluídos',
        'cancelados' => 'Cancelados',
    ];

    public function index(Request $request): View
    {
        $situacao = $request->string('situacao')->toString();
        $situacao = array_key_exists($situacao, self::SITUACOES) ? $situacao : null;
        $todos = DemoData::pedidosDaMinhaConta();

        $naSituacao = fn (string $chave) => fn (array $p): bool => match ($chave) {
            'concluidos' => $p['status'] === StatusPedido::Concluido,
            'cancelados' => $p['status'] === StatusPedido::Cancelado,
            default => ! in_array($p['status'], [StatusPedido::Concluido, StatusPedido::Cancelado], true),
        };

        return view('loja.conta.pedidos.index', [
            'pedidos' => $situacao ? $todos->filter($naSituacao($situacao))->values() : $todos,
            'situacoes' => collect(self::SITUACOES)->map(fn (string $label, string $chave): array => [
                'label' => $label,
                'total' => $todos->filter($naSituacao($chave))->count(),
            ]),
            'situacaoAtual' => $situacao,
            'total' => $todos->count(),
        ]);
    }

    public function show(string $pedido): View
    {
        $pedido = DemoData::pedidoDaMinhaConta((int) $pedido);

        abort_if($pedido === null, 404);

        return view('loja.conta.pedidos.show', ['pedido' => $pedido]);
    }
}
