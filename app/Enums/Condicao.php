<?php

namespace App\Enums;

enum Condicao: string
{
    case Novo = 'novo';
    case Seminovo = 'seminovo';

    public function label(): string
    {
        return match ($this) {
            self::Novo => 'Novo',
            self::Seminovo => 'Seminovo',
        };
    }
}
