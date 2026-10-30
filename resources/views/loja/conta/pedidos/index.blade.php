<x-loja-layout titulo="Meus pedidos">
    <x-loja.conta titulo="Meus pedidos" descricao="Os pedidos que o vendedor registrou para você depois da conversa no WhatsApp. Exemplos fictícios.">
        <div role="group" aria-label="Filtrar pedidos" class="no-scrollbar -mx-4 flex gap-2 overflow-x-auto px-4 md:mx-0 md:px-0">
            <a href="{{ route('cliente.pedidos.index') }}" class="chip flex-none text-sm {{ $situacaoAtual ? '' : 'chip-on' }}">Todos <b>{{ $total }}</b></a>
            @foreach ($situacoes as $chave => $situacao)
                <a href="{{ route('cliente.pedidos.index', ['situacao' => $chave]) }}" class="chip flex-none text-sm {{ $situacaoAtual === $chave ? 'chip-on' : '' }}">
                    {{ $situacao['label'] }} <b>{{ $situacao['total'] }}</b>
                </a>
            @endforeach
        </div>

        <div class="mt-4 flex flex-col gap-2.5">
            @forelse ($pedidos as $pedido)
                <x-loja.pedido-card :pedido="$pedido" />
            @empty
                <div class="card flex flex-col items-center gap-3 px-6 py-12 text-center">
                    <h2 class="font-display text-2xl font-bold">Nenhum pedido aqui.</h2>
                    <p class="max-w-[380px] text-muted">Quando você combinar uma compra pelo WhatsApp, o vendedor registra o pedido e ele aparece nesta lista.</p>
                    <a href="{{ route('catalogo') }}" class="btn btn-dark mt-2">Ver catálogo</a>
                </div>
            @endforelse
        </div>

        <a href="{{ route('cliente.pedidos.por_data') }}" class="mt-6 inline-flex min-h-[44px] items-center gap-2 font-semibold">
            Ver pedidos por data <x-icone nome="seta" tamanho="18" />
        </a>
    </x-loja.conta>
</x-loja-layout>
