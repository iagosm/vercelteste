<?php

namespace App\View\Components;

use App\Support\DemoData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LojaLayout extends Component
{
    /**
     * @var list<array{label: string, url: string, ativo: bool}>
     */
    public array $navegacao;

    /**
     * @var Collection<int, array{slug: string, nome: string, forma: string, cor: string, ativa: bool}>
     */
    public Collection $categorias;

    /**
     * @var list<array{nome: string, categoria: string, url: string}>
     */
    public array $sugestoes;

    /**
     * Atalhos do menu da conta (cabeçalho e menu do celular).
     *
     * @var list<array{label: string, url: string}>
     */
    public array $linksDaConta;

    /**
     * @var array{instagram: string, instagram_url: string, email: string, email_url: ?string}
     */
    public array $contatos;

    /**
     * @param  bool  $fabElevado  sobe o botão flutuante quando a página tem barra fixa no rodapé (celular)
     */
    public function __construct(
        public ?string $titulo = null,
        public bool $fabElevado = false,
    ) {
        $this->navegacao = [
            ['label' => 'Início', 'url' => route('home'), 'ativo' => request()->routeIs('home')],
            ['label' => 'Catálogo', 'url' => route('catalogo'), 'ativo' => request()->routeIs('catalogo', 'produtos.show')],
            ['label' => 'Sobre nós', 'url' => route('sobre'), 'ativo' => request()->routeIs('sobre')],
            ['label' => 'Contato', 'url' => route('contato'), 'ativo' => request()->routeIs('contato')],
        ];

        $this->categorias = DemoData::categorias()->where('ativa', true)->values();

        $this->sugestoes = DemoData::produtosAtivos()
            ->map(fn (array $produto): array => [
                'nome' => $produto['nome'],
                'categoria' => DemoData::categoria($produto['categoria'])['nome'] ?? '',
                'url' => route('produtos.show', $produto['slug']),
            ])
            ->all();

        $this->linksDaConta = [
            ['label' => 'Minha conta', 'url' => route('cliente.conta')],
            ['label' => 'Meus pedidos', 'url' => route('cliente.pedidos.index')],
            ['label' => 'Pedidos por data', 'url' => route('cliente.pedidos.por_data')],
            ['label' => 'Meu perfil', 'url' => route('cliente.perfil.edit')],
        ];

        $instagram = config('loja.instagram');
        $email = config('loja.email');

        $this->contatos = [
            'instagram' => $instagram,
            'instagram_url' => str_starts_with($instagram, '@')
                ? 'https://www.instagram.com/'.ltrim($instagram, '@')
                : 'https://www.instagram.com/',
            'email' => $email,
            'email_url' => filter_var($email, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$email : null,
        ];
    }

    public function render(): View
    {
        return view('layouts.loja');
    }
}
