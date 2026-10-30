<x-loja-layout>
    {{-- Hero --}}
    <section class="overflow-hidden bg-ink text-snow">
        <div class="wrap grid items-center gap-10 pb-20 pt-[72px] lg:grid-cols-[minmax(0,1.05fr)_minmax(0,.95fr)]">
            <div class="animate-rise">
                <p class="eyebrow text-brand"><span class="h-px w-8 bg-brand"></span>Produtos Apple · atendimento por pessoas</p>
                <h1 class="mt-[26px] font-display text-[clamp(44px,6.2vw,92px)] font-extrabold leading-[.96]">
                    Escolha com calma.<br>A gente cuida <span class="text-brand">do resto.</span>
                </h1>
                <p class="mt-[26px] max-w-[480px] text-[19px] leading-[1.55] text-fog">
                    Veja o catálogo, tire suas dúvidas no WhatsApp e fale com quem entende de Apple. Sem fila, sem pressa e sem formulário.
                </p>
                <div class="mt-[34px] flex flex-wrap gap-3">
                    <a href="{{ route('catalogo') }}" class="btn btn-lime">Ver catálogo <x-icone nome="seta" tamanho="18" /></a>
                    <button type="button" class="btn btn-ghost" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">
                        <x-icone nome="whatsapp" /> Falar no WhatsApp
                    </button>
                </div>
                <ul class="mt-10 flex flex-wrap gap-x-7 gap-y-2.5 text-sm text-fog">
                    @foreach (['Atendimento direto', 'Novos e seminovos', 'Ajuda depois da compra'] as $vantagem)
                        <li class="flex items-center gap-2"><x-icone nome="check" tamanho="16" traco="2.4" class="text-brand" />{{ $vantagem }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="relative grid min-h-[480px] items-center md:min-h-[560px]">
                <div class="absolute right-[70px] top-2.5 w-[min(58%,300px)] animate-float">
                    <x-produto-arte forma="phone" cor="#7C93C9" />
                </div>
                <div class="absolute bottom-2.5 right-0 w-[min(40%,190px)]">
                    <x-produto-arte forma="airpods" cor="#F0F1EE" />
                </div>
                <div class="relative z-[2] mt-[120px] w-full max-w-[360px] overflow-hidden rounded-card border border-ink-300 bg-ink-700" x-data="conversaDemo">
                    <div class="flex items-center gap-3 border-b border-ink-400 px-4 py-3">
                        <span class="grid size-9 place-items-center rounded-full bg-brand font-display font-extrabold text-ink">{{ mb_substr(config('loja.nome'), 0, 1) }}</span>
                        <div class="leading-tight">
                            <p class="text-[15px] font-semibold">{{ config('loja.nome') }} · Vendas</p>
                            <p class="text-xs text-fog transition-colors" :style="{ color: etapa === 2 ? '#B8FF65' : '' }"
                               x-text="etapa === 2 ? 'digitando…' : 'Exemplo ilustrativo de conversa'">Exemplo ilustrativo de conversa</p>
                        </div>
                    </div>
                    {{-- Balões ficam sempre no layout (só somem com opacidade) para o card não mudar de altura --}}
                    <div class="flex flex-col gap-2.5 p-4 text-[14.5px] leading-[1.45]">
                        <p class="max-w-[88%] self-end rounded-[12px_12px_3px_12px] border border-[#2B5A38] bg-[#1D3A25] px-[13px] py-2.5 transition duration-500"
                           :style="etapa >= 1 ? '' : 'opacity: 0; transform: translateY(12px)'">Oi! Vi o iPhone 17 no site. Tem em azul de 256 GB?</p>
                        <div class="grid max-w-[88%] self-start">
                            <span class="flex items-center gap-1 self-start justify-self-start rounded-[12px_12px_12px_3px] border border-ink-300 bg-[#1B241E] px-4 py-3.5 transition duration-300 [grid-area:1/1]"
                                  style="opacity: 0" :style="{ opacity: etapa === 2 ? 1 : 0 }" aria-hidden="true">
                                <i class="size-2 animate-digitando rounded-full bg-fog"></i>
                                <i class="size-2 animate-digitando rounded-full bg-fog [animation-delay:.15s]"></i>
                                <i class="size-2 animate-digitando rounded-full bg-fog [animation-delay:.3s]"></i>
                            </span>
                            <p class="rounded-[12px_12px_12px_3px] border border-ink-300 bg-[#1B241E] px-[13px] py-2.5 transition duration-500 [grid-area:1/1]"
                               :style="etapa >= 3 ? '' : 'opacity: 0; transform: translateY(12px)'">Oi, tudo bem? Vou conferir aqui e já te respondo, com as opções de pagamento.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categorias --}}
    <section class="pb-10 pt-14 md:pt-[88px]">
        <div class="wrap">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-6">
                <h2 class="font-display text-[clamp(32px,4vw,52px)] font-bold leading-none">Por onde você quer começar?</h2>
                <a href="{{ route('catalogo') }}" class="inline-flex min-h-[44px] items-center gap-2.5 font-semibold">Ver tudo <x-icone nome="seta" tamanho="18" /></a>
            </div>
            <div class="grid grid-cols-2 gap-3.5 md:grid-cols-[repeat(auto-fit,minmax(min(180px,100%),1fr))]">
                @foreach ($categorias as $categoria)
                    <a href="{{ route('catalogo', ['categoria' => $categoria['slug']]) }}"
                       aria-label="{{ $categoria['nome'] }}, a partir de {{ Number::currency($categoria['a_partir_de'], precision: 0) }}"
                       class="flex flex-col gap-1.5 rounded-card border border-line bg-white px-5 pb-5 pt-[22px] transition duration-300 hover:-translate-y-1 hover:border-ink">
                        <span class="block rounded-[3px] bg-stage px-[26px] pb-2 pt-3.5">
                            <x-produto-arte :forma="$categoria['forma']" :cor="$categoria['cor']" />
                        </span>
                        <span class="mt-2.5 font-display text-[22px] font-bold">{{ $categoria['nome'] }}</span>
                        <span class="text-[13px] text-muted">A partir de {{ Number::currency($categoria['a_partir_de'], precision: 0) }}</span>
                    </a>
                @endforeach
            </div>
            <p class="mt-3.5 text-[13px] text-muted">Preços demonstrativos.</p>
        </div>
    </section>

    {{-- Destaques --}}
    <section class="pb-14 pt-16 md:pb-24" x-data="{ aba: @js(array_key_first($destaques)) }">
        <div class="wrap">
            <div class="mb-7 flex flex-wrap items-end justify-between gap-6">
                <h2 class="font-display text-[clamp(32px,4vw,52px)] font-bold leading-none">Em destaque</h2>
                <div role="group" aria-label="Filtrar destaques" class="flex flex-wrap gap-2">
                    @foreach ($destaques as $chave => $aba)
                        <button type="button" @click="aba = @js($chave)" :aria-pressed="aba === @js($chave)"
                                :class="aba === @js($chave) ? 'border-ink bg-ink text-brand' : 'border-[#BFC7B8] hover:border-ink'"
                                class="min-h-[46px] rounded-full border px-5 text-[15px] font-semibold transition">
                            {{ $aba['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
            @foreach ($destaques as $chave => $aba)
                <div x-show="aba === @js($chave)" @if (! $loop->first) x-cloak @endif
                     class="grid grid-cols-2 gap-3 md:grid-cols-[repeat(auto-fill,minmax(min(270px,100%),1fr))] md:gap-5">
                    @foreach ($aba['produtos'] as $produto)
                        <x-loja.produto-card :produto="$produto" />
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    {{-- Como funciona --}}
    <section class="bg-ink py-14 text-snow md:py-[104px]">
        <div class="wrap grid items-start gap-10 lg:grid-cols-[minmax(0,.9fr)_minmax(0,1.1fr)] lg:gap-20">
            <div>
                <p class="eyebrow text-brand">Como funciona</p>
                <h2 class="mt-4 font-display text-[clamp(34px,4.4vw,60px)] font-bold leading-none">Comprar aqui é uma conversa, não um formulário.</h2>
                <p class="mt-5 max-w-[440px] text-lg text-fog">Você escolhe no site. Quem fecha a compra com você é uma pessoa, pelo WhatsApp.</p>
            </div>
            <ol class="border-b border-ink-400">
                @foreach ([
                    ['Escolha o aparelho', 'Use a busca e os filtros do catálogo para chegar rápido no que procura.'],
                    ['Chame no WhatsApp', 'A mensagem já vai pronta, com o produto, a cor e a configuração que você escolheu.'],
                    ['Combine com um vendedor', 'Disponibilidade, pagamento e entrega são combinados direto com você.'],
                ] as [$titulo, $texto])
                    <li class="grid grid-cols-[56px_minmax(0,1fr)] gap-2 border-t border-ink-400 py-[26px]">
                        <span class="font-display text-xl font-bold text-brand">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3 class="font-display text-2xl font-bold">{{ $titulo }}</h3>
                            <p class="mt-1.5 text-fog">{{ $texto }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Quem somos --}}
    <section class="py-14 md:py-[104px]">
        <div class="wrap grid items-center gap-10 lg:grid-cols-2 lg:gap-[72px]">
            <div class="grid grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] grid-rows-[200px_200px] gap-3.5">
                <div class="placeholder-photo row-span-2"><x-icone nome="camera" tamanho="32" traco="1.6" />[Foto da equipe]</div>
                <div class="placeholder-photo"><x-icone nome="camera" tamanho="28" traco="1.6" />[Foto da loja]</div>
                <div class="flex items-end rounded-card bg-ink p-5 font-display text-[22px] font-bold leading-[1.1] tracking-[-.02em] text-brand">Atendimento que acompanha você.</div>
            </div>
            <div>
                <p class="eyebrow text-moss">Quem somos</p>
                <h2 class="mt-4 font-display text-[clamp(32px,4vw,54px)] font-bold leading-none">Por trás do site, tem gente.</h2>
                <p class="mt-5 max-w-[480px] text-lg leading-relaxed text-body">
                    Somos uma loja de produtos Apple que acredita em atendimento próximo: você conversa com uma pessoa, tira todas as dúvidas e só decide quando se sentir seguro.
                </p>
                <p class="mt-3 text-[13px] text-muted">Texto de exemplo. Substitua pela história real da loja.</p>
                <a href="{{ route('sobre') }}" class="btn btn-line mt-7">Conhecer a loja</a>
            </div>
        </div>
    </section>

    {{-- Chamada final --}}
    <section class="bg-brand text-ink">
        <div class="wrap flex flex-wrap items-center justify-between gap-8 py-[76px]">
            <div class="max-w-[640px]">
                <h2 class="font-display text-[clamp(32px,4.4vw,60px)] font-extrabold leading-none">Em dúvida entre dois modelos?</h2>
                <p class="mt-3.5 text-[19px] text-[#1E2D1A]">Conte como você usa e receba uma indicação, sem compromisso.</p>
            </div>
            <button type="button" class="btn btn-dark min-h-[60px] px-[34px] text-[17px]" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">
                <x-icone nome="whatsapp" tamanho="22" /> Falar com um vendedor
            </button>
        </div>
    </section>
</x-loja-layout>
