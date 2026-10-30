{{-- Pedido do cliente: card empilhado no celular, linha alinhada em colunas no desktop. --}}
@props(['pedido', 'mostrarData' => true])

<a href="{{ route('cliente.pedidos.show', $pedido['id']) }}"
   {{ $attributes->merge(['class' => 'group flex flex-col gap-3 rounded-card border border-line bg-white p-4 transition hover:border-ink md:grid md:grid-cols-[80px_minmax(0,1fr)_auto_auto] md:items-center md:gap-5 md:px-5']) }}>
    <div class="flex items-center justify-between gap-3 md:contents">
        <b class="text-[15px]">#{{ $pedido['id'] }}</b>
        <x-status-pedido :status="$pedido['status']" class="md:order-3" />
    </div>
    <div class="md:order-2 md:min-w-0">
        <p class="font-semibold leading-snug">{{ $pedido['produto'] }}</p>
        <p class="mt-0.5 text-[13.5px] text-muted">
            @if ($mostrarData){{ $pedido['registrado_em']->format('d/m/Y') }} · @endif{{ Number::currency($pedido['valor'], precision: 0) }}
        </p>
    </div>
    <span class="inline-flex items-center gap-1.5 text-sm font-semibold md:order-4">
        Ver pedido <x-icone nome="seta" tamanho="16" class="transition group-hover:translate-x-0.5" />
    </span>
</a>
