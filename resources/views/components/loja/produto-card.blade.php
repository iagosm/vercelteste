@props(['produto'])

@use('App\Enums\Condicao')
@use('App\Support\DemoData')
@use('App\Support\WhatsApp')

@php
    $seminovo = $produto['condicao'] === Condicao::Seminovo;
    $armazenamentos = $produto['rotulo_opcao'] === 'Armazenamento' ? array_column($produto['opcoes'], 'rotulo') : [];
    $textoArmazenamento = count($armazenamentos) > 1
        ? $armazenamentos[0].' a '.end($armazenamentos)
        : ($armazenamentos[0] ?? '');
    $detalhes = collect([$produto['condicao']->label(), $textoArmazenamento])->filter()->implode(' · ');
    $mensagem = WhatsApp::mensagemCompra($produto['nome'].($seminovo ? ' (seminovo)' : ''));
    $url = route('produtos.show', $produto['slug']);
@endphp

<article {{ $attributes->merge(['class' => 'group flex h-full flex-col rounded-card border border-line bg-white transition hover:border-ink']) }}>
    <a href="{{ $url }}" aria-label="Ver detalhes de {{ $produto['nome'] }}" class="relative block aspect-[1/1.02] w-full overflow-hidden rounded-t-[3px] bg-stage px-3.5 py-3 md:px-7 md:py-5">
        <span class="absolute left-3 top-3 z-[1] flex flex-wrap gap-1.5">
            @if ($produto['selo'])
                <span class="rounded-full bg-brand px-[9px] py-1 text-xs font-bold uppercase tracking-[.05em] text-ink">{{ $produto['selo'] }}</span>
            @endif
            @if ($seminovo)
                <span class="rounded-full bg-ink px-[9px] py-1 text-xs font-bold uppercase tracking-[.05em] text-snow">Seminovo</span>
            @endif
        </span>
        <x-produto-arte :forma="$produto['forma']" :cor="$produto['cores'][0]['hex']" class="transition duration-500 group-hover:-translate-y-1.5 group-hover:scale-[1.04]" />
    </a>

    <div class="flex flex-1 flex-col gap-1.5 px-3.5 pb-4 pt-3.5 md:px-[18px] md:pb-5 md:pt-[18px]">
        <p class="text-xs uppercase tracking-[.12em] text-muted">{{ DemoData::categoria($produto['categoria'])['nome'] ?? '' }}</p>
        <h3 class="font-display text-[21px] font-bold leading-[1.15]">{{ $produto['nome'] }}</h3>
        <p class="text-sm text-muted">{{ $produto['linha'] }}</p>

        <div class="mt-1 flex min-h-[14px] items-center gap-[5px]">
            @foreach (array_slice($produto['cores'], 0, 5) as $cor)
                <span class="size-3.5 rounded-full border border-ink/30" style="background: {{ $cor['hex'] }}" title="{{ $cor['nome'] }}"></span>
            @endforeach
            @if (count($produto['cores']) > 5)
                <span class="text-xs text-muted">+{{ count($produto['cores']) - 5 }}</span>
            @endif
        </div>

        <p class="text-[13px] text-muted">{{ $detalhes }}</p>
        <p class="flex items-center gap-2 text-[13px]">
            <span class="size-2 rounded-full" style="background: {{ $produto['disponibilidade']->cor() }}"></span>
            {{ $produto['disponibilidade']->rotuloCard() }}
        </p>

        <p class="mt-auto pt-3 text-xs text-muted">{{ count($produto['opcoes']) > 1 ? 'A partir de' : 'Preço demonstrativo' }}</p>
        <p class="text-[22px] font-semibold">{{ Number::currency($produto['preco'], precision: 0) }}</p>

        <div class="mt-3 flex gap-2">
            <a href="{{ $url }}" class="inline-flex min-h-[46px] flex-1 items-center justify-center rounded-full border border-ink text-[15px] font-semibold transition hover:bg-ink hover:text-brand">Detalhes</a>
            <button type="button" @click="$store.whatsapp.abrir(@js($mensagem))" aria-label="Comprar {{ $produto['nome'] }} pelo WhatsApp" class="inline-flex min-h-[46px] min-w-[46px] items-center justify-center gap-2 rounded-full bg-brand px-3.5 text-[15px] font-semibold text-ink transition hover:bg-ink hover:text-brand">
                <x-icone nome="whatsapp" />
                <span class="hidden md:inline">Comprar</span>
            </button>
        </div>
    </div>
</article>
