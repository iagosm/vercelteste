@props(['status', 'ponto' => false])

<span {{ $attributes->merge(['class' => 'badge']) }} style="background: {{ $status->corFundo() }}; color: {{ $status->corTexto() }}">
    @if ($ponto)
        <i class="inline-block size-2 rounded-full" style="background: {{ $status->corTexto() }}"></i>
    @endif
    {{ $status->label() }}
</span>
