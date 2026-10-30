<?php

namespace App\Enums;

enum Disponibilidade: string
{
    case EmEstoque = 'em_estoque';
    case PoucasUnidades = 'poucas_unidades';
    case SobEncomenda = 'sob_encomenda';

    /**
     * Rótulo usado no painel.
     */
    public function label(): string
    {
        return match ($this) {
            self::EmEstoque => 'Em estoque',
            self::PoucasUnidades => 'Poucas unidades',
            self::SobEncomenda => 'Sob encomenda',
        };
    }

    /**
     * Rótulo curto usado nos cards da loja.
     */
    public function rotuloCard(): string
    {
        return match ($this) {
            self::EmEstoque => 'Disponível',
            self::PoucasUnidades => 'Poucas unidades',
            self::SobEncomenda => 'Sob encomenda',
        };
    }

    /**
     * Rótulo usado na página do produto.
     */
    public function rotuloProduto(): string
    {
        return match ($this) {
            self::EmEstoque => 'Disponível para pronta entrega',
            self::PoucasUnidades => 'Poucas unidades',
            self::SobEncomenda => 'Sob encomenda',
        };
    }

    /**
     * Agrupamento usado no filtro do catálogo: "poucas unidades" ainda está em estoque.
     */
    public function grupoFiltro(): string
    {
        return $this === self::SobEncomenda ? 'Sob encomenda' : 'Em estoque';
    }

    public function cor(): string
    {
        return match ($this) {
            self::EmEstoque => '#3E9B4F',
            self::PoucasUnidades => '#C98A00',
            self::SobEncomenda => '#6B746D',
        };
    }
}
