<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\CatalogoFiltro;
use App\Support\DemoData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filtro = CatalogoFiltro::fromRequest($request, DemoData::produtosAtivos());

        return view('loja.catalogo', [
            'filtro' => $filtro,
            'produtos' => $filtro->resultados(),
            'facetas' => $filtro->facetas(),
            'categorias' => DemoData::categorias()->where('ativa', true)->values(),
        ]);
    }
}
