<x-loja-layout titulo="Catálogo">
    <section class="wrap min-h-[80vh] pb-14 pt-11 md:pb-28" x-data="{ filtros: false }">
        <nav aria-label="Você está em" class="flex items-center gap-1.5 text-sm text-muted">
            <a href="{{ route('home') }}" class="inline-flex min-h-9 items-center">Início</a>
            <span aria-hidden="true">/</span>
            <span class="text-ink">Catálogo</span>
        </nav>

        <form id="filtros" action="{{ route('catalogo') }}" method="GET" @change="$el.submit()">
            @if ($filtro->categoria)
                <input type="hidden" name="categoria" value="{{ $filtro->categoria }}">
            @endif
            @if ($filtro->busca !== '')
                <input type="hidden" name="q" value="{{ $filtro->busca }}">
            @endif

            <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
                <h1 class="font-display text-[clamp(38px,5vw,68px)] font-extrabold leading-none">{{ $filtro->titulo() }}</h1>
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" class="chip lg:hidden" @click="filtros = true">
                        <x-icone nome="filtros" tamanho="18" />
                        Filtros{{ $filtro->totalFiltrosAtivos() ? ' ('.$filtro->totalFiltrosAtivos().')' : '' }}
                    </button>
                    <label for="ordem" class="text-sm text-muted">Ordenar</label>
                    <select id="ordem" name="ordem" class="min-h-[44px] rounded-full border-line-strong bg-white py-0 pl-4 pr-10 text-[15px] text-ink focus:border-ink focus:ring-0">
                        <option value="destaque" @selected($filtro->ordem === 'destaque')>Destaques</option>
                        <option value="menor" @selected($filtro->ordem === 'menor')>Menor preço</option>
                        <option value="maior" @selected($filtro->ordem === 'maior')>Maior preço</option>
                    </select>
                </div>
            </div>

            <div role="group" aria-label="Categoria" class="no-scrollbar flex gap-2 overflow-x-auto pb-1.5 pt-[22px]">
                <a href="{{ route('catalogo', array_filter(['q' => $filtro->busca])) }}" class="chip {{ $filtro->categoria ? '' : 'chip-on' }}" @if (! $filtro->categoria) aria-current="true" @endif>Todos</a>
                @foreach ($categorias as $categoria)
                    @php($ativa = $filtro->categoria === $categoria['slug'])
                    <a href="{{ route('catalogo', array_filter(['categoria' => $categoria['slug'], 'q' => $filtro->busca])) }}" class="chip {{ $ativa ? 'chip-on' : '' }}" @if ($ativa) aria-current="true" @endif>{{ $categoria['nome'] }}</a>
                @endforeach
            </div>

            <div class="mt-5 grid items-start gap-10 lg:grid-cols-[260px_minmax(0,1fr)]">
                <aside aria-label="Filtros"
                       class="hidden lg:block"
                       :class="filtros ? '!block fixed inset-0 z-40 overflow-auto bg-paper px-4 pb-28 pt-5 lg:static lg:p-0' : ''">
                    <div class="flex items-center justify-between border-b border-line pb-3">
                        <h2 class="font-display text-[22px] font-bold">Filtros</h2>
                        <div class="flex items-center gap-3">
                            @if ($filtro->totalFiltrosAtivos())
                                <a href="{{ route('catalogo', array_filter(['categoria' => $filtro->categoria, 'q' => $filtro->busca])) }}" class="inline-flex min-h-[44px] items-center text-sm font-semibold underline">
                                    Limpar ({{ $filtro->totalFiltrosAtivos() }})
                                </a>
                            @endif
                            <button type="button" class="inline-flex size-11 items-center justify-center rounded-full border border-line-strong lg:hidden" aria-label="Fechar filtros" @click="filtros = false">
                                <x-icone nome="fechar" tamanho="18" />
                            </button>
                        </div>
                    </div>

                    <fieldset class="border-b border-line py-[18px]">
                        <legend class="pb-2.5 font-display text-[17px] font-bold">Faixa de preço</legend>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label for="preco_min" class="text-[13px] text-muted">De (R$)</label>
                                <input id="preco_min" name="preco_min" type="number" min="0" inputmode="numeric" placeholder="0" value="{{ $filtro->precoMinimo }}" class="inp">
                            </div>
                            <div>
                                <label for="preco_max" class="text-[13px] text-muted">Até (R$)</label>
                                <input id="preco_max" name="preco_max" type="number" min="0" inputmode="numeric" placeholder="∞" value="{{ $filtro->precoMaximo }}" class="inp">
                            </div>
                        </div>
                    </fieldset>

                    @foreach ($facetas as $faceta)
                        <fieldset class="border-b border-line py-[18px]">
                            <legend class="pb-1.5 font-display text-[17px] font-bold">{{ $faceta['titulo'] }}</legend>
                            @foreach ($faceta['itens'] as $item)
                                <label class="flex min-h-10 w-full cursor-pointer items-center gap-2.5 text-[15px]">
                                    <input type="checkbox" name="{{ $faceta['chave'] }}[]" value="{{ $item['valor'] }}" @checked($item['marcado']) class="peer sr-only">
                                    <span class="grid size-5 flex-none place-items-center rounded-[3px] border-[1.5px] border-[#8C968E] bg-white text-white peer-checked:border-ink peer-checked:bg-ink peer-checked:text-brand peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-focus">
                                        <x-icone nome="check" tamanho="13" traco="3.5" />
                                    </span>
                                    @if ($item['hex'])
                                        <span class="size-4 rounded-full border border-ink/30" style="background: {{ $item['hex'] }}"></span>
                                    @endif
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <span class="text-[13px] text-muted">{{ $item['total'] }}</span>
                                </label>
                            @endforeach
                        </fieldset>
                    @endforeach

                    <noscript><button type="submit" class="btn btn-dark mt-4 w-full">Aplicar filtros</button></noscript>

                    <div class="fixed inset-x-0 bottom-0 border-t border-line bg-paper px-4 py-3.5 lg:hidden">
                        <button type="button" class="btn btn-dark w-full" @click="filtros = false">Ver {{ $produtos->count() }} produtos</button>
                    </div>
                </aside>

                <div>
                    <p class="mb-5 text-[15px] text-muted">
                        {{ $produtos->count() }} {{ Str::plural('produto', $produtos->count()) }}@if ($filtro->busca !== '') para "{{ $filtro->busca }}"@endif
                    </p>

                    @if ($produtos->isNotEmpty())
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-[repeat(auto-fill,minmax(min(250px,100%),1fr))] md:gap-5">
                            @foreach ($produtos as $produto)
                                <x-loja.produto-card :produto="$produto" />
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center gap-3.5 rounded-card border border-line-strong bg-white px-7 py-14 text-center">
                            <h2 class="font-display text-[30px] font-bold">Não achamos esse aparelho com esses filtros.</h2>
                            <p class="max-w-[440px] text-muted">Tente remover algum filtro, ou pergunte a um vendedor: ele pode estar disponível sob encomenda.</p>
                            <div class="mt-2 flex flex-wrap justify-center gap-3">
                                <button type="button" class="btn btn-dark" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">Falar com um vendedor</button>
                                <a href="{{ route('catalogo') }}" class="btn btn-line">Limpar tudo</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </section>
</x-loja-layout>
