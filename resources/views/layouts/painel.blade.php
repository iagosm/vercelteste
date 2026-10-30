<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        @include('layouts.partials.head', ['titulo' => $titulo.' · Painel'])
    </head>
    <body class="bg-paper font-sans leading-normal text-ink antialiased" x-data>
        <div class="flex min-h-screen flex-col bg-paper lg:flex-row">
            <aside class="flex flex-none flex-col bg-ink px-4 py-3 text-snow lg:sticky lg:top-0 lg:h-screen lg:w-[250px] lg:py-[22px]">
                <a href="{{ route('painel.dashboard') }}" class="px-1.5 pb-3 lg:pb-[22px]">
                    <x-logo subtitulo="Painel" :tamanho="32" />
                </a>

                <nav aria-label="Painel" class="no-scrollbar flex gap-1 overflow-x-auto lg:flex-col">
                    @foreach ($navegacao as $item)
                        <a href="{{ $item['url'] }}" @if ($item['ativo']) aria-current="page" @endif
                           class="flex min-h-[44px] flex-none items-center gap-3 whitespace-nowrap rounded-card px-3.5 text-[15px] font-medium transition hover:bg-ink-600 hover:text-snow {{ $item['ativo'] ? 'bg-ink-600 text-brand' : 'text-[#C9CFCA]' }}">
                            <x-icone :nome="$item['icone']" traco="1.8" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if ($item['contador'])
                                <span class="rounded-full bg-brand px-2 py-px text-xs font-bold text-ink">{{ $item['contador'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="mt-auto hidden flex-col gap-2.5 border-t border-ink-500 px-2 pt-4 lg:flex">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex min-h-[44px] items-center gap-2 font-semibold text-brand">
                        <x-icone nome="externo" tamanho="18" /> Ver a loja
                    </a>
                    <form method="POST" action="{{ route('painel.sair') }}">
                        @csrf
                        <button type="submit" class="inline-flex min-h-[44px] items-center gap-2 font-semibold text-snow">
                            <x-icone nome="sair" tamanho="18" /> Sair
                        </button>
                    </form>
                    <p class="text-xs leading-normal text-fog">Protótipo visual. Sem banco de dados. Todos os dados são fictícios.</p>
                </div>
            </aside>

            <main class="min-w-0 flex-1 px-4 pb-14 pt-5 lg:px-10 lg:pb-[72px] lg:pt-7">
                <header class="mb-7 flex flex-col items-start justify-between gap-4 lg:flex-row lg:items-center">
                    <div>
                        <p class="text-[13px] text-muted">{{ $secao }}</p>
                        <h1 class="font-display text-[34px] font-bold leading-[1.1]">{{ $titulo }}</h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="tag bg-[#FFF1CC] text-[#5C4300]">Dados fictícios</span>
                        <span class="flex items-center gap-2.5 text-sm">
                            <span class="grid size-9 place-items-center rounded-full bg-ink font-display font-bold text-brand">{{ mb_strtoupper(mb_substr($usuario, 0, 1)) }}</span>
                            <span class="flex flex-col leading-tight"><b>{{ $usuario }}</b><span class="text-xs text-muted">Administrador</span></span>
                        </span>
                        <form method="POST" action="{{ route('painel.sair') }}">
                            @csrf
                            <button type="submit" class="btn btn-line btn-sm bg-white">Sair</button>
                        </form>
                    </div>
                </header>

                <div class="animate-rise">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @if (session('status'))
            <div role="status" x-data="{ visivel: true }" x-init="setTimeout(() => visivel = false, 3200)" x-show="visivel" x-transition
                 class="fixed bottom-5 right-5 z-[70] max-w-[340px] animate-rise rounded-card border border-ink-300 bg-ink px-[18px] py-3.5 text-[14.5px] text-snow">
                <span class="font-bold text-brand">Protótipo · </span>{{ session('status') }}
            </div>
        @endif
    </body>
</html>
