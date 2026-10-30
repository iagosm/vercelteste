@props(['subtitulo' => 'Especialista Apple', 'tamanho' => 34])

<span {{ $attributes->merge(['class' => 'flex items-center gap-[11px]']) }}>
    <svg width="{{ $tamanho }}" height="{{ $tamanho }}" viewBox="0 0 34 34" aria-hidden="true">
        <circle cx="17" cy="17" r="17" fill="#B8FF65"/>
        <text x="17" y="24" text-anchor="middle" font-family="Bricolage Grotesque, Instrument Sans, sans-serif" font-weight="800" font-size="21" fill="#0B110D">G</text>
    </svg>
    <span class="flex flex-col leading-[1.05]">
        <span class="font-display text-[25px] font-bold">{{ config('loja.nome') }}</span>
        @if ($subtitulo)
            <span class="text-[11px] uppercase tracking-[.14em] text-fog">{{ $subtitulo }}</span>
        @endif
    </span>
</span>
