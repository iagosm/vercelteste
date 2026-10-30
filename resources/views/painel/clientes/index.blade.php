@php($colunas = 'grid-template-columns: minmax(160px,1.2fr) 150px 120px 90px 150px 90px')

<x-painel-layout titulo="Clientes" secao="Relacionamento">
    <form method="GET" action="{{ route('painel.clientes.index') }}" class="relative max-w-[380px]">
        <label for="busca-clientes" class="sr-only">Buscar clientes</label>
        <x-icone nome="busca" tamanho="18" class="absolute left-3.5 top-[13px] text-muted" />
        <input id="busca-clientes" name="q" type="search" value="{{ request('q') }}" placeholder="Buscar por nome ou telefone" class="inp min-h-[44px] pl-10">
    </form>

    <div class="card mt-4 overflow-x-auto">
        <div class="min-w-[820px]">
            <div class="table-head" role="row" style="{{ $colunas }}">
                <span>Cliente</span><span>Telefone</span><span>Cadastro</span><span>Pedidos</span><span>Último pedido</span><span></span>
            </div>
            @forelse ($clientes as $cliente)
                <div class="table-row" role="row" style="{{ $colunas }}">
                    <a href="{{ route('painel.clientes.show', $cliente['id']) }}" class="inline-flex min-h-[44px] items-center font-semibold underline">{{ $cliente['nome'] }}</a>
                    <span>{{ $cliente['telefone'] }}</span>
                    <span class="text-muted">{{ $cliente['desde'] }}</span>
                    <span>{{ $cliente['total_pedidos'] }}</span>
                    <span class="text-muted">{{ $cliente['ultimo_pedido']?->format('d/m H:i') ?? '—' }}</span>
                    <a href="{{ route('painel.clientes.show', $cliente['id']) }}" class="btn btn-line btn-sm" aria-label="Ver {{ $cliente['nome'] }}">Ver</a>
                </div>
            @empty
                <p class="px-5 py-8 text-muted">Nenhum cliente encontrado.</p>
            @endforelse
        </div>
    </div>
</x-painel-layout>
