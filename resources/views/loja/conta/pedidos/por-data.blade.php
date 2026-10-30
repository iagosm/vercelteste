<x-loja-layout titulo="Pedidos por data">
    <x-loja.conta titulo="Pedidos por data" descricao="Escolha um período para ver o que você pediu em cada dia.">
        <div role="group" aria-label="Período" class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4 md:mx-0 md:px-0">
            @foreach ($atalhos as $chave => $atalho)
                <a href="{{ route('cliente.pedidos.por_data', ['periodo' => $chave]) }}" class="chip flex-none text-sm {{ $atalhoAtual === $chave ? 'chip-on' : '' }}">{{ $atalho['label'] }}</a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('cliente.pedidos.por_data') }}" class="card mt-3 grid grid-cols-2 gap-3 p-4 md:flex md:items-end md:p-5">
            <div class="md:w-[200px]">
                <label for="de" class="lbl text-[13px] text-body">De</label>
                <input id="de" name="de" type="date" value="{{ $de }}" class="inp">
            </div>
            <div class="md:w-[200px]">
                <label for="ate" class="lbl text-[13px] text-body">Até</label>
                <input id="ate" name="ate" type="date" value="{{ $ate }}" class="inp">
            </div>
            <button type="submit" class="btn btn-dark col-span-2 min-h-[48px] md:col-span-1">Filtrar</button>
            @if ($errors->any())
                <p role="alert" class="col-span-2 text-sm font-semibold text-rust md:self-center">{{ $errors->first() }}</p>
            @endif
        </form>

        <p class="mt-5 text-[15px] text-muted">
            {{ $quantidade }} {{ Str::plural('pedido', $quantidade) }} no período · <b class="text-ink">{{ Number::currency($valorTotal, precision: 0) }}</b>
        </p>

        <div class="mt-4 flex flex-col gap-7">
            @forelse ($dias as $dia)
                @php
                    $nomeDoDia = match (true) {
                        $dia['data']->isToday() => 'Hoje',
                        $dia['data']->isYesterday() => 'Ontem',
                        default => ucfirst($dia['data']->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY')),
                    };
                @endphp
                <section aria-labelledby="dia-{{ $dia['data']->toDateString() }}">
                    <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1 border-b border-line pb-2">
                        <h2 id="dia-{{ $dia['data']->toDateString() }}" class="font-display text-lg font-bold md:text-xl">{{ $nomeDoDia }}</h2>
                        <p class="text-[13px] text-muted">
                            {{ $dia['pedidos']->count() }} {{ Str::plural('pedido', $dia['pedidos']->count()) }} · {{ Number::currency($dia['total'], precision: 0) }}
                        </p>
                    </div>
                    <div class="mt-3 flex flex-col gap-2.5">
                        @foreach ($dia['pedidos'] as $pedido)
                            <x-loja.pedido-card :pedido="$pedido" :mostrar-data="false" />
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="card flex flex-col items-center gap-3 px-6 py-12 text-center">
                    <h2 class="font-display text-2xl font-bold">Nenhum pedido nesse período.</h2>
                    <p class="max-w-[380px] text-muted">Tente um período maior ou veja todos os seus pedidos.</p>
                    <a href="{{ route('cliente.pedidos.por_data', ['periodo' => 'tudo']) }}" class="btn btn-line mt-2">Ver tudo</a>
                </div>
            @endforelse
        </div>
    </x-loja.conta>
</x-loja-layout>
