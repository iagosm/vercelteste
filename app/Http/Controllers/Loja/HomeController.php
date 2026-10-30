<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $produtos = DemoData::produtosAtivos();

        $categorias = DemoData::categorias()
            ->where('ativa', true)
            ->map(fn (array $categoria): array => $categoria + [
                'a_partir_de' => $produtos->where('categoria', $categoria['slug'])->min('preco'),
            ])
            ->filter(fn (array $categoria): bool => $categoria['a_partir_de'] !== null)
            ->values();

        return view('loja.home', [
            'categorias' => $categorias,
            'destaques' => DemoData::destaques(),
        ]);
    }
}
