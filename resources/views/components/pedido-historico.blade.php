@props(['pedido', 'responsavel' => true])

<ol {{ $attributes }}>
    @foreach (array_reverse($pedido['historico']) as $evento)
        <li class="grid grid-cols-[14px_minmax(0,1fr)] gap-3.5 pb-[18px] last:pb-0">
            <span class="flex flex-col items-center">
                <span class="mt-[5px] size-3 rounded-full" style="background: {{ $evento['status']->corBarra() }}"></span>
                <span class="mt-1 w-0.5 flex-1 bg-line"></span>
            </span>
            <span>
                <span class="font-semibold">{{ $evento['status']->label() }}</span><br>
                <span class="text-[13px] text-muted">{{ $evento['em']->format('d/m/Y H:i') }}@if ($responsavel) · {{ $evento['por'] }}@endif</span>
            </span>
        </li>
    @endforeach
</ol>
