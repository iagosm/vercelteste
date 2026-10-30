<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClienteAnotacaoController extends Controller
{
    public function store(Request $request, string $cliente): RedirectResponse
    {
        abort_if(DemoData::cliente((int) $cliente) === null, 404);

        $request->validate([
            'texto' => ['required', 'string', 'max:1000'],
        ]);

        return back()->with('status', 'Anotação recebida. Nada foi salvo.');
    }
}
