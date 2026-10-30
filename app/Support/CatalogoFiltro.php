<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Filtros do catálogo: busca, categoria, facetas, faixa de preço e ordenação.
 *
 * As facetas são calculadas sobre o "escopo" (categoria + busca), então só
 * aparecem opções que existem nos produtos visíveis.
 *
 * @phpstan-import-type Produto from DemoData
 */
class CatalogoFiltro
{
    public const FACETAS = ['condicao', 'armazenamento', 'cor', 'disponibilidade', 'modelo'];

    /**
     * @param  Collection<int, Produto>  $produtos
     * @param  array<string, list<string>>  $selecionados
     */
    public function __construct(
        public Collection $produtos,
        public ?string $categoria = null,
        public string $busca = '',
        public string $ordem = 'destaque',
        public array $selecionados = [],
        public ?int $precoMinimo = null,
        public ?int $precoMaximo = null,
    ) {}

    /**
     * @param  Collection<int, Produto>  $produtos
     */
    public static function fromRequest(Request $request, Collection $produtos): self
    {
        $selecionados = collect(self::FACETAS)
            ->mapWithKeys(fn (string $faceta): array => [$faceta => array_values(array_filter((array) $request->query($faceta, []), 'is_string'))])
            ->all();

        return new self(
            produtos: $produtos,
            categoria: $request->string('categoria')->toString() ?: null,
            busca: $request->string('q')->trim()->toString(),
            ordem: in_array($request->query('ordem'), ['menor', 'maior'], true) ? $request->query('ordem') : 'destaque',
            selecionados: $selecionados,
            precoMinimo: $request->filled('preco_min') ? $request->integer('preco_min') : null,
            precoMaximo: $request->filled('preco_max') ? $request->integer('preco_max') : null,
        );
    }

    /**
     * Produtos da categoria e da busca, antes das facetas.
     *
     * @return Collection<int, Produto>
     */
    public function escopo(): Collection
    {
        $termo = $this->normalizar($this->busca);

        return $this->produtos
            ->when($this->categoria, fn (Collection $produtos): Collection => $produtos->where('categoria', $this->categoria))
            ->when($termo !== '', fn (Collection $produtos): Collection => $produtos->filter(
                fn (array $produto): bool => str_contains($this->textoBuscavel($produto), $termo)
            ))
            ->values();
    }

    /**
     * @return Collection<int, Produto>
     */
    public function resultados(): Collection
    {
        $resultados = $this->escopo()->filter(function (array $produto): bool {
            foreach ($this->selecionados as $faceta => $valores) {
                if ($valores !== [] && ! collect($valores)->contains(fn (string $valor): bool => $this->combina($produto, $faceta, $valor))) {
                    return false;
                }
            }

            if ($this->precoMinimo !== null && $produto['preco'] < $this->precoMinimo) {
                return false;
            }

            return $this->precoMaximo === null || $produto['preco'] <= $this->precoMaximo;
        });

        $ordenados = match ($this->ordem) {
            'menor' => $resultados->sortBy('preco'),
            'maior' => $resultados->sortByDesc('preco'),
            default => $resultados->sortByDesc('destaque'),
        };

        return $ordenados->values();
    }

    /**
     * @return list<array{chave: string, titulo: string, itens: list<array{valor: string, label: string, total: int, marcado: bool, hex: ?string}>}>
     */
    public function facetas(): array
    {
        $escopo = $this->escopo();
        $facetas = [];

        $condicoes = $escopo->map(fn (array $p): string => $p['condicao']->value)->unique()->values();
        $facetas[] = $this->faceta('condicao', 'Condição', $condicoes->mapWithKeys(fn (string $v): array => [$v => ucfirst($v)])->all(), $escopo);

        $armazenamentos = $escopo->where('rotulo_opcao', 'Armazenamento')
            ->flatMap(fn (array $p): array => array_column($p['opcoes'], 'rotulo'))
            ->unique()
            ->sortBy(fn (string $rotulo): float => (float) $rotulo * (str_contains($rotulo, 'TB') ? 1000 : 1))
            ->values();
        $facetas[] = $this->faceta('armazenamento', 'Armazenamento', $armazenamentos->combine($armazenamentos)->all(), $escopo);

        $cores = $escopo->flatMap(fn (array $p): array => $p['cores'])->unique('familia')->values();
        $facetas[] = $this->faceta('cor', 'Cor', $cores->pluck('familia', 'familia')->all(), $escopo, $cores->pluck('hex', 'familia')->all());

        $grupos = $escopo->map(fn (array $p): string => $p['disponibilidade']->grupoFiltro())->unique()->values();
        $facetas[] = $this->faceta('disponibilidade', 'Disponibilidade', $grupos->combine($grupos)->all(), $escopo);

        if ($this->categoria) {
            $modelos = $escopo->pluck('modelo')->filter()->unique()->values();
            $facetas[] = $this->faceta('modelo', 'Modelo', $modelos->combine($modelos)->all(), $escopo);
        }

        return array_values(array_filter($facetas));
    }

    public function totalFiltrosAtivos(): int
    {
        return collect($this->selecionados)->sum(fn (array $valores): int => count($valores))
            + ($this->precoMinimo !== null ? 1 : 0)
            + ($this->precoMaximo !== null ? 1 : 0);
    }

    public function titulo(): string
    {
        if ($this->busca !== '') {
            return 'Resultados';
        }

        return $this->categoria ? (DemoData::categoria($this->categoria)['nome'] ?? 'Catálogo') : 'Catálogo completo';
    }

    /**
     * Uma faceta só aparece com duas ou mais opções, ou quando já tem algo marcado.
     *
     * @param  array<string, string>  $opcoes  valor => rótulo
     * @param  Collection<int, Produto>  $escopo
     * @param  array<string, string>  $hexes
     * @return array{chave: string, titulo: string, itens: list<array{valor: string, label: string, total: int, marcado: bool, hex: ?string}>}|null
     */
    private function faceta(string $chave, string $titulo, array $opcoes, Collection $escopo, array $hexes = []): ?array
    {
        $marcados = $this->selecionados[$chave] ?? [];

        if (count($opcoes) < 2 && $marcados === []) {
            return null;
        }

        $itens = collect($opcoes)->map(fn (string $label, string $valor): array => [
            'valor' => $valor,
            'label' => $label,
            'total' => $escopo->filter(fn (array $p): bool => $this->combina($p, $chave, $valor))->count(),
            'marcado' => in_array($valor, $marcados, true),
            'hex' => $hexes[$valor] ?? null,
        ])->values()->all();

        return compact('chave', 'titulo', 'itens');
    }

    /**
     * @param  Produto  $produto
     */
    private function combina(array $produto, string $faceta, string $valor): bool
    {
        return match ($faceta) {
            'condicao' => $produto['condicao']->value === $valor,
            'armazenamento' => $produto['rotulo_opcao'] === 'Armazenamento' && in_array($valor, array_column($produto['opcoes'], 'rotulo'), true),
            'cor' => in_array($valor, array_column($produto['cores'], 'familia'), true),
            'disponibilidade' => $produto['disponibilidade']->grupoFiltro() === $valor,
            'modelo' => $produto['modelo'] === $valor,
            default => true,
        };
    }

    /**
     * @param  Produto  $produto
     */
    private function textoBuscavel(array $produto): string
    {
        $categoria = DemoData::categoria($produto['categoria'])['nome'] ?? '';

        return $this->normalizar($produto['nome'].' '.$produto['linha'].' '.$categoria);
    }

    private function normalizar(string $texto): string
    {
        return Str::of($texto)->ascii()->lower()->toString();
    }
}
