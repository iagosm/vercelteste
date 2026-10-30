<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\View\View;

class ContatoController extends Controller
{
    public function __invoke(): View
    {
        return view('loja.contato', [
            'perguntas' => DemoData::perguntasFrequentes(),
            'categorias' => DemoData::categorias()->where('ativa', true)->pluck('nome'),
        ]);
    }
}
