<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        @include('layouts.partials.head', ['titulo' => $titulo])
    </head>
    <body class="bg-paper font-sans leading-normal text-ink antialiased" data-whatsapp="{{ config('loja.whatsapp') }}" x-data>
        <div class="bg-brand px-4 py-2 text-center text-[13px] font-semibold leading-snug text-ink">
            Protótipo · produtos, preços, estoque e contatos são demonstrativos
        </div>

        <header class="sticky top-0 z-30 border-b border-ink-500 bg-ink text-snow" x-data="{ menu: false }">
            <div class="wrap flex flex-wrap items-center gap-x-6 gap-y-3 py-3">
                <a href="{{ route('home') }}" aria-label="{{ config('loja.nome') }}, página inicial">
                    <x-logo />
                </a>

                <nav class="ml-2 hidden gap-0.5 lg:flex" aria-label="Principal">
                    @foreach ($navegacao as $item)
                        <a href="{{ $item['url'] }}" @if ($item['ativo']) aria-current="page" @endif
                           class="relative rounded-full px-3.5 py-2.5 text-[15px] font-medium transition hover:bg-ink-700 {{ $item['ativo'] ? 'text-brand' : 'text-snow' }}">
                            {{ $item['label'] }}
                            <span class="absolute inset-x-3.5 bottom-0.5 h-0.5 {{ $item['ativo'] ? 'bg-brand' : 'bg-transparent' }}"></span>
                        </a>
                    @endforeach
                </nav>

                <form action="{{ route('catalogo') }}" method="GET" role="search"
                      class="relative order-3 basis-full lg:order-none lg:ml-auto lg:max-w-[340px] lg:flex-1 lg:basis-[150px]"
                      x-data="busca(@js($sugestoes), @js(request('q', '')))" @click.outside="aberto = false">
                    <label for="busca" class="sr-only">Pesquisar produtos</label>
                    <x-icone nome="busca" tamanho="18" class="absolute left-4 top-3.5 text-fog" />
                    <input id="busca" name="q" type="search" autocomplete="off" placeholder="Buscar iPhone, MacBook, AirPods…"
                           x-model="termo" @input="aberto = true" @keydown.escape="aberto = false"
                           class="h-[46px] w-full rounded-full border border-ink-400 bg-ink-700 pl-11 pr-[18px] text-[15px] text-snow placeholder:text-fog focus:border-ink-200 focus:ring-0">

                    <div x-show="aberto && termo.trim()" x-cloak class="absolute inset-x-0 top-[52px] z-40 rounded-card border border-ink-300 bg-ink-700 py-1.5">
                        <template x-for="produto in resultados" :key="produto.url">
                            <a :href="produto.url" class="flex min-h-[46px] w-full items-center justify-between gap-3 px-4 text-[15px] text-snow hover:bg-ink-600">
                                <span x-text="produto.nome"></span>
                                <span class="text-[13px] text-fog" x-text="produto.categoria"></span>
                            </a>
                        </template>
                        <template x-if="resultados.length">
                            <button type="submit" class="flex min-h-[46px] w-full items-center px-4 text-[15px] font-semibold text-brand hover:bg-ink-600">Ver todos os resultados</button>
                        </template>
                        <template x-if="!resultados.length">
                            <p class="px-4 py-3.5 text-sm text-fog">Nada encontrado. Pergunte a um vendedor.</p>
                        </template>
                    </div>
                </form>

                <div class="ml-auto lg:ml-0">
                    <button type="button" class="btn btn-lime min-h-[46px] px-4 text-[15px] md:px-5"
                            @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">
                        <x-icone nome="whatsapp" />
                        <span class="hidden md:inline">Falar com vendedor</span>
                        <span class="sr-only md:hidden">Falar com vendedor pelo WhatsApp</span>
                    </button>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    @if (session('cliente'))
                        {{-- Abre ao passar o mouse e também no clique/toque e pelo teclado --}}
                        <div class="relative" x-data="{ aberto: false }"
                             @mouseenter="aberto = true" @mouseleave="aberto = false"
                             @click.outside="aberto = false" @keydown.escape="aberto = false; $refs.botao.focus()">
                            <button type="button" x-ref="botao" @click="aberto = ! aberto" :aria-expanded="aberto" aria-haspopup="true"
                                    aria-label="Menu da conta de {{ session('cliente.nome') }}"
                                    class="flex min-h-[46px] items-center gap-2.5 rounded-full border border-ink-200 py-0 pl-1.5 pr-3 text-[15px] font-semibold transition hover:border-brand"
                                    :class="aberto && 'border-brand'">
                                <span class="grid size-[34px] place-items-center rounded-full bg-brand font-display font-extrabold text-ink">{{ mb_strtoupper(mb_substr(session('cliente.nome'), 0, 1)) }}</span>
                                <span>{{ str(session('cliente.nome'))->before(' ') }}</span>
                                <x-icone nome="chevron" tamanho="16" class="transition" ::class="aberto && 'rotate-180'" />
                            </button>

                            {{-- O pt-2 cobre o espaço entre o botão e o menu, para o hover não fechar no caminho --}}
                            <div x-show="aberto" x-cloak x-transition.origin.top.right class="absolute right-0 top-full z-40 w-[250px] pt-2">
                                <div class="overflow-hidden rounded-card border border-ink-300 bg-ink-700 shadow-[0_18px_40px_rgba(0,0,0,.35)]">
                                    <div class="border-b border-ink-400 px-4 py-3">
                                        <p class="font-semibold">{{ session('cliente.nome') }}</p>
                                        <p class="truncate text-[13px] text-fog">{{ session('cliente.email') }}</p>
                                    </div>
                                    <nav aria-label="Conta" class="py-1.5">
                                        @foreach ($linksDaConta as $link)
                                            <a href="{{ $link['url'] }}" class="flex min-h-[44px] items-center px-4 text-[15px] text-snow transition hover:bg-ink-600 hover:text-brand">{{ $link['label'] }}</a>
                                        @endforeach
                                    </nav>
                                    <form action="{{ route('cliente.sair') }}" method="POST" class="border-t border-ink-400 py-1.5">
                                        @csrf
                                        <button type="submit" class="flex min-h-[44px] w-full items-center gap-2.5 px-4 text-[15px] text-snow transition hover:bg-ink-600 hover:text-brand">
                                            <x-icone nome="sair" tamanho="18" /> Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('cliente.entrar') }}" class="btn btn-ghost min-h-[46px] px-[18px] text-[15px]">
                            <x-icone nome="usuario" />
                            <span>Entrar</span>
                        </a>
                    @endif
                </div>

                <button type="button" class="inline-flex size-[46px] items-center justify-center rounded-full border border-ink-400 lg:hidden"
                        @click="menu = ! menu" :aria-expanded="menu" aria-label="Abrir menu">
                    <x-icone nome="menu" x-show="! menu" />
                    <x-icone nome="fechar" x-show="menu" x-cloak />
                </button>
            </div>

            <nav x-show="menu" x-cloak class="wrap flex animate-rise flex-col pb-4 lg:hidden" aria-label="Menu">
                @foreach ($navegacao as $item)
                    <a href="{{ $item['url'] }}" class="flex min-h-[52px] items-center justify-between border-t border-ink-500 text-xl font-semibold {{ $item['ativo'] ? 'text-brand' : '' }}">
                        {{ $item['label'] }}
                        <x-icone nome="seta" tamanho="18" />
                    </a>
                @endforeach
                @if (session('cliente'))
                    <p class="border-t border-ink-500 pb-1 pt-4 text-[13px] uppercase tracking-[.12em] text-fog">Olá, {{ str(session('cliente.nome'))->before(' ') }}</p>
                    @foreach ($linksDaConta as $link)
                        <a href="{{ $link['url'] }}" class="flex min-h-[48px] items-center text-lg font-semibold text-brand">{{ $link['label'] }}</a>
                    @endforeach
                    <form action="{{ route('cliente.sair') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex min-h-[52px] w-full items-center border-t border-ink-500 text-xl font-semibold">Sair</button>
                    </form>
                @else
                    <a href="{{ route('cliente.entrar') }}" class="flex min-h-[52px] items-center border-t border-ink-500 text-xl font-semibold text-brand">Entrar ou criar conta</a>
                @endif
            </nav>

            <div class="border-t border-ink-500 bg-ink-700">
                <nav class="wrap no-scrollbar flex gap-1 overflow-x-auto" aria-label="Categorias">
                    @foreach ($categorias as $categoria)
                        @php($categoriaAtiva = request()->routeIs('catalogo') && request('categoria') === $categoria['slug'])
                        <a href="{{ route('catalogo', ['categoria' => $categoria['slug']]) }}"
                           class="flex-none whitespace-nowrap px-3.5 py-2.5 text-sm font-medium transition hover:text-snow {{ $categoriaAtiva ? 'text-brand' : 'text-fog' }}">
                            {{ $categoria['nome'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </header>

        <main>
            @if (session('status'))
                <div class="wrap pt-6">
                    <p role="status" class="rounded-card border border-ink-300 bg-ink px-[18px] py-3.5 text-[14.5px] text-snow">
                        <span class="font-bold text-brand">Protótipo · </span>{{ session('status') }}
                    </p>
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="bg-ink text-snow">
            <div class="wrap pb-8 pt-[72px]">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)_minmax(0,1.1fr)_minmax(0,1fr)]">
                    <div>
                        <x-logo :subtitulo="null" />
                        <p class="mt-4 max-w-[300px] text-fog">Especialista Apple com atendimento direto. [Logotipo provisório]</p>
                        <div class="mt-5 flex gap-2.5">
                            <button type="button" aria-label="Falar pelo WhatsApp"
                                    @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')"
                                    class="grid size-11 place-items-center rounded-full bg-brand text-ink transition hover:bg-brand-soft">
                                <x-icone nome="whatsapp" />
                            </button>
                            <a href="{{ $contatos['instagram_url'] }}" target="_blank" rel="noopener" aria-label="Instagram da loja"
                               class="grid size-11 place-items-center rounded-full border border-ink-200 transition hover:border-brand hover:text-brand">
                                <x-icone nome="instagram" />
                            </a>
                            <a href="{{ $contatos['email_url'] ?? route('contato') }}" aria-label="Enviar e-mail"
                               class="grid size-11 place-items-center rounded-full border border-ink-200 transition hover:border-brand hover:text-brand">
                                <x-icone nome="email" />
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="text-[13px] uppercase tracking-[.12em] text-fog">Fale com a gente</p>
                        <ul class="mt-3.5 flex flex-col gap-1">
                            <li>
                                <button type="button" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')"
                                        class="inline-flex min-h-[40px] items-center gap-2.5 text-left hover:text-brand">
                                    <x-icone nome="whatsapp" tamanho="18" class="flex-none text-brand" /> {{ config('loja.whatsapp_exibicao') }}
                                </button>
                            </li>
                            <li>
                                <a href="{{ $contatos['instagram_url'] }}" target="_blank" rel="noopener" class="inline-flex min-h-[40px] items-center gap-2.5 hover:text-brand">
                                    <x-icone nome="instagram" tamanho="18" class="flex-none text-brand" /> {{ $contatos['instagram'] }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ $contatos['email_url'] ?? route('contato') }}" class="inline-flex min-h-[40px] items-center gap-2.5 break-all hover:text-brand">
                                    <x-icone nome="email" tamanho="18" class="flex-none text-brand" /> {{ $contatos['email'] }}
                                </a>
                            </li>
                            <li class="flex min-h-[40px] items-center gap-2.5 text-fog">
                                <x-icone nome="relogio" tamanho="18" class="flex-none text-brand" /> {{ config('loja.horario') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-[13px] uppercase tracking-[.12em] text-fog">Navegar</p>
                        <ul class="mt-3.5 grid grid-cols-2 gap-x-6 gap-y-1">
                            @foreach ($navegacao as $item)
                                <li><a href="{{ $item['url'] }}" class="inline-flex min-h-9 items-center hover:text-brand">{{ $item['label'] }}</a></li>
                            @endforeach
                            @foreach ($categorias as $categoria)
                                <li><a href="{{ route('catalogo', ['categoria' => $categoria['slug']]) }}" class="inline-flex min-h-9 items-center hover:text-brand">{{ $categoria['nome'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <p class="text-[13px] uppercase tracking-[.12em] text-fog">Loja</p>
                        <p class="mt-3.5 flex gap-2.5"><x-icone nome="local" tamanho="18" class="mt-0.5 flex-none text-brand" />{{ config('loja.endereco') }}</p>
                        <p class="mt-2 text-fog">CNPJ {{ config('loja.cnpj') }}</p>
                    </div>
                </div>
                <p class="mt-12 border-t border-ink-500 pt-6 text-[13px] leading-relaxed text-fog">
                    Apple, iPhone, MacBook, iPad, Apple Watch e AirPods são marcas de seus respectivos proprietários. Protótipo: produtos, preços e estoque são demonstrativos.
                </p>
            </div>
        </footer>

        <button type="button" aria-label="Falar com um vendedor pelo WhatsApp"
                @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')"
                class="fixed right-3.5 z-[45] grid size-[54px] place-items-center rounded-full border-2 border-ink bg-brand text-ink transition hover:scale-105 hover:bg-brand-soft md:right-5 md:size-[62px] {{ $fabElevado ? 'bottom-[92px] md:bottom-6' : 'bottom-6' }}">
            <x-icone nome="whatsapp" tamanho="30" />
        </button>

        <x-loja.whatsapp-modal />
    </body>
</html>
