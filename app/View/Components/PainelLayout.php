<?php

namespace App\View\Components;

use App\Enums\StatusPedido;
use App\Support\DemoData;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PainelLayout extends Component
{
    /**
     * @var list<array{label: string, url: string, icone: string, ativo: bool, contador: int}>
     */
    public array $navegacao;

    public string $usuario;

    /**
     * @param  string  $secao  texto pequeno acima do título (ex.: "Operação")
     */
    public function __construct(
        public string $titulo,
        public string $secao = '',
    ) {
        $novos = DemoData::pedidos()->where('status', StatusPedido::Novo)->count();

        $this->navegacao = [
            ['label' => 'Visão geral', 'url' => route('painel.dashboard'), 'icone' => 'visao-geral', 'ativo' => request()->routeIs('painel.dashboard'), 'contador' => 0],
            ['label' => 'Pedidos', 'url' => route('painel.pedidos.index'), 'icone' => 'pedidos', 'ativo' => request()->routeIs('painel.pedidos.*'), 'contador' => $novos],
            ['label' => 'Produtos', 'url' => route('painel.produtos.index'), 'icone' => 'produtos', 'ativo' => request()->routeIs('painel.produtos.*'), 'contador' => 0],
            ['label' => 'Clientes', 'url' => route('painel.clientes.index'), 'icone' => 'clientes', 'ativo' => request()->routeIs('painel.clientes.*'), 'contador' => 0],
            ['label' => 'Categorias', 'url' => route('painel.categorias.index'), 'icone' => 'categorias', 'ativo' => request()->routeIs('painel.categorias.*'), 'contador' => 0],
            ['label' => 'Configurações', 'url' => route('painel.configuracoes.edit'), 'icone' => 'configuracoes', 'ativo' => request()->routeIs('painel.configuracoes.*'), 'contador' => 0],
        ];

        $this->usuario = session('painel_usuario', 'Admin demo');
    }

    public function render(): View
    {
        return view('layouts.painel');
    }
}
