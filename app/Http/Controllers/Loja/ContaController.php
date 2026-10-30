<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContaController extends Controller
{
    public function show(Request $request): View
    {
        $pedidos = DemoData::pedidosDaMinhaConta();

        return view('loja.conta.show', [
            'cliente' => $request->session()->get('cliente'),
            'recentes' => $pedidos->take(3),
            'emAndamento' => $pedidos->filter(fn (array $p): bool => $p['status']->emAberto())->count(),
            'totalPedidos' => $pedidos->count(),
        ]);
    }
}
