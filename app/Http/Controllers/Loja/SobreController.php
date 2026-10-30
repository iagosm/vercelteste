<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SobreController extends Controller
{
    public function __invoke(): View
    {
        return view('loja.sobre');
    }
}
