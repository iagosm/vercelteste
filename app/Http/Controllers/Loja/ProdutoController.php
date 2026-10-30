<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function show(string $slug): View
    {
        $produto = DemoData::produto($slug);

        abort_if($produto === null || ! $produto['ativo'], 404);

        $ativos = DemoData::produtosAtivos()->where('slug', '!=', $produto['slug']);

        $relacionados = $ativos->where('categoria', $produto['categoria'])
            ->concat($ativos->where('categoria', '!=', $produto['categoria'])->where('destaque', true))
            ->take(4)
            ->values();

        return view('loja.produto', [
            'produto' => $produto,
            'categoria' => DemoData::categoria($produto['categoria']),
            'relacionados' => $relacionados,
        ]);
    }
}
