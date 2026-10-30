@props(['pedido'])

@use('App\Enums\StatusPedido')

@php
    $etapas = array_filter(StatusPedido::cases(), fn (StatusPedido $s): bool => $s !== StatusPedido::Cancelado);
    $alcancadas = array_map(fn (array $h): StatusPedido => $h['status'], $pedido['historico']);
@endphp

<div {{ $attributes }}>
    <ol aria-label="Etapas do pedido" class="grid grid-cols-5 gap-1.5">
        @foreach ($etapas as $etapa)
            @php
                $atual = $pedido['status'] === $etapa;
                $alcancada = in_array($etapa, $alcancadas, true);
            @endphp
            <li class="flex flex-col gap-2" @if ($atual) aria-current="step" @endif>
                <span class="h-1.5 rounded-sm" style="background: {{ $alcancada ? ($atual ? $etapa->corBarra() : '#0B110D') : '#D5DACF' }}"></span>
                <span class="text-[11.5px] leading-tight md:text-[12.5px] {{ $atual ? 'font-bold' : 'font-medium' }} {{ $alcancada ? 'text-ink' : 'text-[#6B746D]' }}">{{ $etapa->label() }}</span>
            </li>
        @endforeach
    </ol>
    {{ $slot }}
</div>
