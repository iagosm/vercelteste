<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PainelGuestLayout extends Component
{
    public function __construct(
        public ?string $titulo = null,
    ) {}

    public function render(): View
    {
        return view('layouts.painel-guest');
    }
}
