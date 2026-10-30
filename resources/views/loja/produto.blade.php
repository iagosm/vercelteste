@use('App\Enums\Condicao')

@php
    $seminovo = $produto['condicao'] === Condicao::Seminovo;
    $origemZoom = $produto['forma'] === 'phone' ? '38% 22%' : '50% 50%';
    $vistas = [
        ['label' => 'frente', 'transformacao' => 'none', 'origem' => '50% 50%'],
        ['label' => 'detalhe', 'transformacao' => 'scale(1.8)', 'origem' => $origemZoom],
        ['label' => 'ângulo', 'transformacao' => 'rotate(-12deg) scale(.92)', 'origem' => '50% 50%'],
    ];
    $dadosAlpine = [
        'nome' => $produto['nome'],
        'sufixoCondicao' => $seminovo ? ' (seminovo)' : '',
        'preco' => $produto['preco'],
        'cores' => array_map(fn (array $cor): array => ['nome' => $cor['nome']], $produto['cores']),
        'opcoes' => $produto['opcoes'],
    ];
@endphp

<x-loja-layout :titulo="$produto['nome']" fab-elevado>
    <div x-data="produto(@js($dadosAlpine))">
        <section class="wrap pb-14 pt-7 md:pb-[72px]">
            <nav aria-label="Você está em" class="mb-[22px] flex flex-wrap items-center gap-1.5 text-sm text-muted">
                <a href="{{ route('home') }}" class="inline-flex min-h-10 items-center">Início</a><span aria-hidden="true">/</span>
                <a href="{{ route('catalogo') }}" class="inline-flex min-h-10 items-center">Catálogo</a><span aria-hidden="true">/</span>
                <a href="{{ route('catalogo', ['categoria' => $categoria['slug']]) }}" class="inline-flex min-h-10 items-center">{{ $categoria['nome'] }}</a><span aria-hidden="true">/</span>
                <span class="text-ink">{{ $produto['nome'] }}</span>
            </nav>

            <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,.95fr)] lg:gap-16">
                {{-- Galeria --}}
                <div class="grid grid-cols-[72px_minmax(0,1fr)] items-start gap-3.5">
                    <div class="flex flex-col gap-2.5">
                        @foreach ($vistas as $indice => $vista)
                            <button type="button" @click="vista = {{ $indice }}" :aria-pressed="vista === {{ $indice }}" aria-label="Imagem: {{ $vista['label'] }}"
                                    :class="vista === {{ $indice }} ? 'border-ink' : 'border-line'"
                                    class="block aspect-square w-full overflow-hidden rounded-[3px] border bg-white p-1.5 transition">
                                <span class="block" style="transform: {{ $vista['transformacao'] }}; transform-origin: {{ $vista['origem'] }}">
                                    @foreach ($produto['cores'] as $indiceCor => $cor)
                                        <x-produto-arte :forma="$produto['forma']" :cor="$cor['hex']" x-show="cor === {{ $indiceCor }}" />
                                    @endforeach
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <div class="relative grid aspect-[1/1.1] place-items-center overflow-hidden rounded-card border border-line bg-stage">
                        @foreach ($vistas as $indice => $vista)
                            <div class="w-[72%] transition-transform duration-500" x-show="vista === {{ $indice }}" @if ($indice > 0) x-cloak @endif
                                 style="transform: {{ $vista['transformacao'] }}; transform-origin: {{ $vista['origem'] }}">
                                @foreach ($produto['cores'] as $indiceCor => $cor)
                                    <x-produto-arte :forma="$produto['forma']" :cor="$cor['hex']" x-show="cor === {{ $indiceCor }}" />
                                @endforeach
                            </div>
                        @endforeach
                        <div class="absolute left-4 top-4 flex gap-1.5">
                            @if ($produto['selo'])
                                <span class="rounded-full bg-brand px-2.5 py-1 text-xs font-bold uppercase tracking-[.05em] text-ink">{{ $produto['selo'] }}</span>
                            @endif
                            @if ($seminovo)
                                <span class="rounded-full bg-ink px-2.5 py-1 text-xs font-bold uppercase tracking-[.05em] text-snow">Seminovo</span>
                            @endif
                        </div>
                        <span class="absolute bottom-3 left-4 text-xs text-muted">Imagem ilustrativa</span>
                    </div>
                </div>

                {{-- Informações e compra --}}
                <div>
                    <p class="eyebrow text-moss">{{ $categoria['nome'] }}</p>
                    <h1 class="mt-3.5 font-display text-[clamp(34px,4.2vw,58px)] font-extrabold leading-none">{{ $produto['nome'] }}</h1>
                    <p class="mt-3 text-lg text-muted">{{ $produto['linha'] }}</p>
                    <div class="mt-6 flex flex-wrap items-baseline gap-x-3.5 gap-y-1.5">
                        <p class="font-display text-[44px] font-bold leading-none" x-text="precoFormatado">{{ Number::currency($produto['preco'], precision: 0) }}</p>
                        <p class="text-sm text-muted">Preço demonstrativo</p>
                    </div>
                    <p class="mt-[18px] max-w-[520px] leading-relaxed text-body">{{ $produto['descricao'] }}</p>

                    <div class="mt-7 border-t border-line pt-[26px]">
                        @if (count($produto['cores']) > 1)
                            <p class="text-sm text-muted">Cor · <span class="font-semibold text-ink" x-text="nomeCor">{{ $produto['cores'][0]['nome'] }}</span></p>
                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                @foreach ($produto['cores'] as $indiceCor => $cor)
                                    <button type="button" @click="cor = {{ $indiceCor }}" :aria-pressed="cor === {{ $indiceCor }}" aria-label="{{ $cor['nome'] }}"
                                            :class="cor === {{ $indiceCor }} ? 'border-ink' : 'border-transparent'"
                                            class="size-10 rounded-full border-2 p-1 transition">
                                        <span class="block size-full rounded-full border border-ink/25" style="background: {{ $cor['hex'] }}"></span>
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @if (count($produto['opcoes']) > 1)
                            <p class="mt-[22px] text-sm text-muted">{{ $produto['rotulo_opcao'] }}</p>
                            <div class="mt-2.5 flex flex-wrap gap-2">
                                @foreach ($produto['opcoes'] as $indiceOpcao => $opcao)
                                    <button type="button" @click="opcao = {{ $indiceOpcao }}" :aria-pressed="opcao === {{ $indiceOpcao }}"
                                            :class="opcao === {{ $indiceOpcao }} ? 'border-ink bg-ink text-brand' : 'border-line-strong bg-white hover:border-ink'"
                                            class="min-h-[48px] rounded-full border px-5 text-[15px] font-medium transition">
                                        {{ $opcao['rotulo'] }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <p class="mt-[22px] flex flex-wrap items-center gap-x-2.5 gap-y-1 text-[15px]">
                            <span class="size-[9px] rounded-full" style="background: {{ $produto['disponibilidade']->cor() }}"></span>
                            {{ $produto['disponibilidade']->rotuloProduto() }}
                            <span class="text-[13px] text-muted">· disponibilidade demonstrativa</span>
                        </p>
                    </div>

                    <div class="mt-[26px] rounded-card bg-ink p-6 text-snow">
                        <p class="text-sm text-fog">Sua escolha</p>
                        <p class="mt-0.5 font-semibold" x-text="resumo"></p>
                        <button type="button" class="btn btn-lime mt-[18px] min-h-[60px] w-full text-[17px]" @click="comprar()">
                            <x-icone nome="whatsapp" tamanho="22" /> Comprar pelo WhatsApp
                        </button>
                        <div class="mt-2.5 flex flex-wrap gap-2">
                            <button type="button" class="btn btn-ghost min-h-[48px] flex-[1_1_180px] px-4 text-[15px]" @click="consultarDisponibilidade()">Consultar disponibilidade</button>
                            <button type="button" class="btn btn-ghost min-h-[48px] flex-[1_1_180px] px-4 text-[15px]" @click="tirarDuvidas()">Tirar dúvidas</button>
                        </div>
                        <p class="mt-3.5 text-[13px] text-fog">Sem checkout no site: pagamento e entrega são combinados com o vendedor.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Especificações --}}
        <section class="border-y border-line bg-white">
            <div class="wrap grid gap-10 py-14 lg:grid-cols-[minmax(0,.8fr)_minmax(0,1.2fr)] lg:gap-[72px] lg:pb-20 lg:pt-[72px]">
                <div>
                    <p class="eyebrow text-moss">Detalhes</p>
                    <h2 class="mt-3.5 font-display text-[clamp(30px,3.6vw,46px)] font-bold leading-none">Especificações</h2>
                    <p class="mt-3 text-[13px] text-muted">Dados demonstrativos para o protótipo.</p>
                </div>
                <dl class="border-b border-line">
                    @foreach ($produto['especificacoes'] as $chave => $valor)
                        <div class="grid grid-cols-[minmax(0,.7fr)_minmax(0,1.3fr)] gap-4 border-t border-line py-4">
                            <dt class="text-muted">{{ $chave }}</dt>
                            <dd>{{ $valor }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>

        <section class="wrap pb-14 pt-14 md:pt-20">
            <div class="grid gap-12 lg:grid-cols-3">
                <div><h3 class="font-display text-[22px] font-bold">Atendimento personalizado</h3><p class="mt-2 text-muted">Um vendedor responde no WhatsApp e acompanha toda a compra.</p></div>
                <div><h3 class="font-display text-[22px] font-bold">Garantia e procedência</h3><p class="mt-2 text-muted">[A definir pela loja]. As condições reais entram aqui.</p></div>
                <div><h3 class="font-display text-[22px] font-bold">Suporte depois da compra</h3><p class="mt-2 text-muted">Ajuda com ativação e dúvidas de uso.</p></div>
            </div>
        </section>

        @if ($relacionados->isNotEmpty())
            <section class="wrap pb-14 pt-6 md:pb-24">
                <h2 class="mb-7 font-display text-[clamp(28px,3.4vw,42px)] font-bold leading-none">Você também pode gostar</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-[repeat(auto-fill,minmax(min(250px,100%),1fr))] md:gap-5">
                    @foreach ($relacionados as $relacionado)
                        <x-loja.produto-card :produto="$relacionado" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Barra fixa de compra no celular --}}
        <div class="sticky bottom-0 z-[15] flex items-center justify-between gap-3 border-t border-ink-400 bg-ink px-4 py-3 text-snow md:hidden">
            <div>
                <p class="text-xs text-fog">Preço demonstrativo</p>
                <p class="font-display text-[22px] font-bold leading-[1.1]" x-text="precoFormatado">{{ Number::currency($produto['preco'], precision: 0) }}</p>
            </div>
            <button type="button" class="btn btn-lime px-5" @click="comprar()">Comprar no WhatsApp</button>
        </div>
    </div>
</x-loja-layout>
