<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Pedidos do cliente agrupados por dia, com filtro de período.
 */
class PedidoPorDataController extends Controller
{
    /**
     * Atalhos de período: chave => quantidade de dias (null = sem limite).
     *
     * @var array<string, array{label: string, dias: ?int}>
     */
    private const ATALHOS = [
        'hoje' => ['label' => 'Hoje', 'dias' => 0],
        '7-dias' => ['label' => '7 dias', 'dias' => 7],
        '30-dias' => ['label' => '30 dias', 'dias' => 30],
        '90-dias' => ['label' => '90 dias', 'dias' => 90],
        'tudo' => ['label' => 'Tudo', 'dias' => null],
    ];

    public function __invoke(Request $request): View
    {
        $request->validate([
            'de' => ['nullable', 'date'],
            'ate' => ['nullable', 'date', ...($request->filled('de') ? ['after_or_equal:de'] : [])],
        ], [
            'ate.after_or_equal' => 'A data final precisa ser igual ou depois da inicial.',
        ]);

        $personalizado = $request->filled('de') || $request->filled('ate');
        $periodo = $request->string('periodo', 'tudo')->toString();
        $atalho = match (true) {
            $personalizado => null,
            array_key_exists($periodo, self::ATALHOS) => $periodo,
            default => 'tudo',
        };

        [$inicio, $fim] = $personalizado
            ? [$request->date('de')?->startOfDay(), $request->date('ate')?->endOfDay()]
            : $this->intervaloDoAtalho($atalho);

        $pedidos = DemoData::pedidosDaMinhaConta()
            ->filter(fn (array $p): bool => (! $inicio || $p['registrado_em']->gte($inicio)) && (! $fim || $p['registrado_em']->lte($fim)));

        $dias = $pedidos
            ->groupBy(fn (array $p): string => $p['registrado_em']->toDateString())
            ->map(fn ($doDia, string $data): array => [
                'data' => Carbon::parse($data),
                'pedidos' => $doDia->values(),
                'total' => $doDia->sum('valor'),
            ])
            ->sortByDesc('data')
            ->values();

        return view('loja.conta.pedidos.por-data', [
            'dias' => $dias,
            'quantidade' => $pedidos->count(),
            'valorTotal' => $pedidos->sum('valor'),
            'atalhos' => self::ATALHOS,
            'atalhoAtual' => $atalho,
            'de' => $request->query('de'),
            'ate' => $request->query('ate'),
        ]);
    }

    /**
     * @return array{0: ?Carbon, 1: ?Carbon}
     */
    private function intervaloDoAtalho(string $atalho): array
    {
        $dias = self::ATALHOS[$atalho]['dias'];

        if ($dias === null) {
            return [null, null];
        }

        return [now()->subDays($dias)->startOfDay(), now()->endOfDay()];
    }
}
