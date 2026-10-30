<?php

namespace App\Http\Controllers\Painel;

use App\Enums\StatusPedido;
use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $pedidos = DemoData::pedidos();
        $produtos = DemoData::produtos();
        $nomes = DemoData::clientes()->pluck('nome', 'id');
        $porStatus = fn (StatusPedido $status): Collection => $pedidos->where('status', $status);
        $concluidos = $porStatus(StatusPedido::Concluido);

        $indicadores = [
            ['label' => 'Aguardando atendimento', 'valor' => $porStatus(StatusPedido::Novo)->count(), 'dica' => 'Pedidos com status Novo', 'url' => route('painel.pedidos.index', ['status' => StatusPedido::Novo->value]), 'destaque' => true],
            ['label' => 'Aguardando confirmação', 'valor' => $porStatus(StatusPedido::AguardandoConfirmacao)->count(), 'dica' => 'Cliente decidindo', 'url' => route('painel.pedidos.index', ['status' => StatusPedido::AguardandoConfirmacao->value]), 'destaque' => false],
            ['label' => 'Confirmados', 'valor' => $porStatus(StatusPedido::Confirmado)->count(), 'dica' => 'Falta entregar', 'url' => route('painel.pedidos.index', ['status' => StatusPedido::Confirmado->value]), 'destaque' => false],
            ['label' => 'Pedidos registrados', 'valor' => $pedidos->count(), 'dica' => 'Todos os status', 'url' => route('painel.pedidos.index'), 'destaque' => false],
            ['label' => 'Produtos ativos', 'valor' => $produtos->where('ativo', true)->count(), 'dica' => 'de '.$produtos->count().' cadastrados', 'url' => route('painel.produtos.index'), 'destaque' => false],
            ['label' => 'Estoque baixo', 'valor' => $produtos->where('estoque', '<=', 1)->count(), 'dica' => 'Produtos com 1 unidade ou menos', 'url' => route('painel.produtos.index'), 'destaque' => false],
            ['label' => 'Clientes', 'valor' => $nomes->count(), 'dica' => 'Cadastrados', 'url' => route('painel.clientes.index'), 'destaque' => false],
        ];

        $atencao = $pedidos
            ->filter(fn (array $p): bool => $p['status'] === StatusPedido::Novo
                || ($p['status'] === StatusPedido::AguardandoConfirmacao && $p['idade_horas'] >= 24))
            ->map(fn (array $p): array => $p + [
                'cliente' => $nomes[$p['cliente_id']],
                'motivo' => $p['status'] === StatusPedido::Novo
                    ? "Sem resposta há {$p['idade_horas']} h"
                    : "Cliente decidindo há {$p['idade_horas']} h",
            ])
            ->values();

        $atividades = $pedidos
            ->flatMap(fn (array $p): array => array_map(fn (array $h): array => $h + [
                'texto' => "Pedido #{$p['id']} · {$h['status']->label()} ({$nomes[$p['cliente_id']]})",
            ], $p['historico']))
            ->sortByDesc('em')
            ->take(7)
            ->values();

        $colunas = collect(StatusPedido::cases())->map(fn (StatusPedido $status): array => [
            'status' => $status,
            'total' => $porStatus($status)->count(),
            'soma' => $porStatus($status)->sum('valor'),
            'pedidos' => $porStatus($status)->take(4)->map(fn (array $p): array => $p + [
                'cliente' => str($nomes[$p['cliente_id']])->before(' ')->toString(),
                'idade' => $this->idade($p['idade_horas']),
                'atrasado' => in_array($status, [StatusPedido::Novo, StatusPedido::AguardandoConfirmacao], true) && $p['idade_horas'] >= 24,
            ])->values(),
        ]);

        $valores = [
            ['label' => 'Em negociação', 'valor' => $pedidos->filter(fn (array $p): bool => $p['status']->emAberto())->sum('valor'), 'cor' => '#E8B93A'],
            ['label' => 'Confirmado, a entregar', 'valor' => $porStatus(StatusPedido::Confirmado)->sum('valor'), 'cor' => '#3E9B4F'],
            ['label' => 'Concluído', 'valor' => $concluidos->sum('valor'), 'cor' => '#0B110D'],
            ['label' => 'Ticket médio (concluídos)', 'valor' => $concluidos->isEmpty() ? null : (int) round($concluidos->avg('valor')), 'cor' => '#A6ADA7'],
        ];

        $origens = collect(DemoData::ORIGENS)->map(fn (string $origem): array => [
            'label' => $origem,
            'total' => $pedidos->where('origem', $origem)->count(),
            'percentual' => $pedidos->isEmpty() ? 0 : (int) round($pedidos->where('origem', $origem)->count() / $pedidos->count() * 100),
        ]);

        return view('painel.dashboard', [
            'indicadores' => $indicadores,
            'atencao' => $atencao,
            'atividades' => $atividades,
            'colunas' => $colunas,
            'valores' => $valores,
            'origens' => $origens,
            'estoqueBaixo' => $produtos->where('estoque', '<=', 1)->take(5)->values(),
        ]);
    }

    private function idade(int $horas): string
    {
        if ($horas < 1) {
            return 'Agora há pouco';
        }

        if ($horas < 24) {
            return "Há {$horas} h";
        }

        $dias = (int) round($horas / 24);

        return 'Há '.$dias.($dias === 1 ? ' dia' : ' dias');
    }
}
