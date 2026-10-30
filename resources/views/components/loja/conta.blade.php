{{-- Moldura das páginas de "Minha conta": título, abas e conteúdo. --}}
@props(['titulo', 'descricao' => null])

@php
    $abas = [
        ['label' => 'Resumo', 'url' => route('cliente.conta'), 'ativo' => request()->routeIs('cliente.conta')],
        ['label' => 'Meus pedidos', 'url' => route('cliente.pedidos.index'), 'ativo' => request()->routeIs('cliente.pedidos.index', 'cliente.pedidos.show')],
        ['label' => 'Pedidos por data', 'url' => route('cliente.pedidos.por_data'), 'ativo' => request()->routeIs('cliente.pedidos.por_data')],
        ['label' => 'Meu perfil', 'url' => route('cliente.perfil.edit'), 'ativo' => request()->routeIs('cliente.perfil.*')],
    ];
@endphp

<section class="wrap pb-14 pt-8 md:pb-[88px] md:pt-16">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow text-moss">Minha conta</p>
            <h1 class="mt-3 font-display text-[clamp(34px,4.6vw,64px)] font-extrabold leading-none">{{ $titulo }}</h1>
            @if ($descricao)
                <p class="mt-3 max-w-[560px] text-[15px] text-muted md:text-base">{{ $descricao }}</p>
            @endif
        </div>
        <form method="POST" action="{{ route('cliente.sair') }}" class="hidden md:block">
            @csrf
            <button type="submit" class="btn btn-line">Sair da conta</button>
        </form>
    </div>

    <nav aria-label="Minha conta" class="no-scrollbar -mx-4 mt-6 flex gap-2 overflow-x-auto px-4 md:mx-0 md:mt-8 md:px-0">
        @foreach ($abas as $aba)
            <a href="{{ $aba['url'] }}" class="chip flex-none {{ $aba['ativo'] ? 'chip-on' : '' }}" @if ($aba['ativo']) aria-current="page" @endif>{{ $aba['label'] }}</a>
        @endforeach
    </nav>

    <div class="mt-6 md:mt-8">
        {{ $slot }}
    </div>

    <form method="POST" action="{{ route('cliente.sair') }}" class="mt-10 md:hidden">
        @csrf
        <button type="submit" class="btn btn-line w-full">Sair da conta</button>
    </form>
</section>
