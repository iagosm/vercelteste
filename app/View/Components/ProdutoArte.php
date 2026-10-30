<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Ilustração provisória do produto, usada até existirem fotos reais.
 */
class ProdutoArte extends Component
{
    public string $escura;

    public string $clara;

    public function __construct(
        public string $forma = 'phone',
        public string $cor = '#A8A093',
        public string $destaque = '#B8FF65',
    ) {
        $luminancia = $this->luminancia($cor);

        $this->escura = $this->misturar($cor, 0, $luminancia > 0.75 ? 0.22 : 0.3);
        $this->clara = $this->misturar($cor, 255, $luminancia < 0.3 ? 0.3 : 0.45);
    }

    public function render(): View
    {
        return view('components.produto-arte');
    }

    /**
     * @return array{int, int, int}
     */
    private function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $valor = hexdec($hex);

        return [($valor >> 16) & 255, ($valor >> 8) & 255, $valor & 255];
    }

    private function luminancia(string $hex): float
    {
        [$r, $g, $b] = $this->rgb($hex);

        return (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    }

    /**
     * Aproxima a cor de preto (0) ou branco (255) na proporção indicada.
     */
    private function misturar(string $hex, int $alvo, float $proporcao): string
    {
        return '#'.collect($this->rgb($hex))
            ->map(fn (int $canal): string => str_pad(dechex((int) round($canal + ($alvo - $canal) * $proporcao)), 2, '0', STR_PAD_LEFT))
            ->implode('');
    }
}
