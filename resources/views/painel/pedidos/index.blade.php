@use('App\Enums\StatusPedido')
@use('App\Support\DemoData')

@php($colunas = 'grid-template-columns: 80px minmax(130px,1fr) minmax(220px,1.6fr) 110px 110px 150px 200px 70px')

<x-painel-layout titulo="Pedidos" secao="Operação">
    <div x-data="{ registrar: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('painel.pedidos.index') }}" class="relative max-w-[380px] flex-[1_1_260px]">
                @if ($statusAtual)
                    <input type="hidden" name="status" value="{{ $statusAtual->value }}">
                @endif
                <label for="busca-pedidos" class="sr-only">Buscar pedidos</label>
                <x-icone nome="busca" tamanho="18" class="absolute left-3.5 top-[13px] text-muted" />
                <input id="busca-pedidos" name="q" type="search" value="{{ request('q') }}" placeholder="Buscar por pedido, cliente ou produto" class="inp min-h-[44px] pl-10">
            </form>
            <button type="button" class="btn btn-dark min-h-[44px] px-5 text-[15px]" @click="registrar = true">
                <x-icone nome="mais" tamanho="18" /> Registrar pedido
            </button>
        </div>

        <div role="group" aria-label="Filtrar por status" class="no-scrollbar flex gap-2 overflow-x-auto py-4">
            <a href="{{ route('painel.pedidos.index', array_filter(['q' => request('q')])) }}" class="chip text-sm {{ $statusAtual ? '' : 'chip-on' }}">Todos <b>{{ $total }}</b></a>
            @foreach (StatusPedido::cases() as $status)
                <a href="{{ route('painel.pedidos.index', array_filter(['status' => $status->value, 'q' => request('q')])) }}" class="chip text-sm {{ $statusAtual === $status ? 'chip-on' : '' }}">
                    {{ $status->label() }} <b>{{ $contagem[$status->value] ?? 0 }}</b>
                </a>
            @endforeach
        </div>

        <p class="-mt-1 mb-3 text-sm text-muted">
            {{ $pedidos->count() }} {{ Str::plural('pedido', $pedidos->count()) }} · {{ Number::currency($pedidos->sum('valor'), precision: 0) }}
        </p>

        <div class="card overflow-x-auto">
            <div class="min-w-[1080px]">
                <div class="table-head" role="row" style="{{ $colunas }}">
                    <span>Pedido</span><span>Cliente</span><span>Produto e configuração</span><span>Registrado</span><span>Valor</span><span>Origem</span><span>Status</span><span></span>
                </div>
                @forelse ($pedidos as $pedido)
                    <div class="table-row" role="row" style="{{ $colunas }}">
                        <a href="{{ route('painel.pedidos.show', $pedido['id']) }}" class="inline-flex min-h-[44px] items-center font-bold underline">#{{ $pedido['id'] }}</a>
                        <span>{{ $pedido['cliente'] }}</span>
                        <span>{{ $pedido['produto'] }}</span>
                        <span class="text-muted">{{ $pedido['registrado_em']->format('d/m H:i') }}</span>
                        <span>{{ Number::currency($pedido['valor'], precision: 0) }}</span>
                        <span class="text-muted">{{ $pedido['origem'] }}</span>
                        <span>
                            <x-status-pedido :status="$pedido['status']" ponto />
                            @if ($pedido['status'] === StatusPedido::Novo && $pedido['idade_horas'] >= 24)
                                <br><span class="text-[12.5px] font-semibold text-rust">Responder</span>
                            @endif
                        </span>
                        <a href="{{ route('painel.pedidos.show', $pedido['id']) }}" class="btn btn-line btn-sm" aria-label="Ver detalhes do pedido #{{ $pedido['id'] }}">Ver</a>
                    </div>
                @empty
                    <p class="px-5 py-8 text-muted">Nenhum pedido com esses filtros.</p>
                @endforelse
            </div>
        </div>

        {{-- Registrar pedido --}}
        <div x-show="registrar" x-cloak class="fixed inset-0 z-50 grid place-items-center p-4" @keydown.escape.window="registrar = false">
            <button type="button" aria-label="Fechar" class="absolute inset-0 bg-[rgba(5,9,6,.6)]" @click="registrar = false"></button>
            <form method="POST" action="{{ route('painel.pedidos.store') }}" role="dialog" aria-modal="true" aria-label="Registrar pedido"
                  class="relative max-h-[calc(100vh-32px)] w-full max-w-[560px] animate-rise overflow-auto rounded-card bg-white p-7"
                  x-data="{ precos: @js(DemoData::produtos()->pluck('preco', 'slug')), produto: @js(old('produto', 'iphone-17-pro')), valor: @js(old('valor', 8490)) }">
                @csrf
                <h2 class="font-display text-[26px] font-bold">Registrar pedido</h2>
                <p class="mt-1.5 text-[14.5px] text-muted">Use depois que a conversa no WhatsApp tiver começado. O pedido entra como Novo.</p>

                <div class="mt-5 flex flex-col gap-3.5">
                    <div>
                        <label for="novo-cliente" class="lbl text-[13px] text-body">Cliente</label>
                        <select id="novo-cliente" name="cliente_id" class="inp min-h-[44px]">
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente['id'] }}" @selected(old('cliente_id') == $cliente['id'])>{{ $cliente['nome'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="novo-produto" class="lbl text-[13px] text-body">Produto</label>
                        <select id="novo-produto" name="produto" class="inp min-h-[44px]" x-model="produto" @change="valor = precos[produto]">
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto['slug'] }}">{{ $produto['nome'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="novo-configuracao" class="lbl text-[13px] text-body">Configuração combinada</label>
                        <input id="novo-configuracao" name="configuracao" type="text" value="{{ old('configuracao') }}" placeholder="Ex.: 256 GB · Azul" class="inp min-h-[44px]">
                    </div>
                    <div class="grid gap-3.5 sm:grid-cols-2">
                        <div>
                            <label for="novo-valor" class="lbl text-[13px] text-body">Valor estimado (R$)</label>
                            <input id="novo-valor" name="valor" type="number" min="0" x-model="valor" class="inp min-h-[44px]">
                        </div>
                        <div>
                            <label for="novo-origem" class="lbl text-[13px] text-body">Origem</label>
                            <select id="novo-origem" name="origem" class="inp min-h-[44px]">
                                @foreach (DemoData::ORIGENS as $origem)
                                    <option value="{{ $origem }}" @selected(old('origem') === $origem)>{{ $origem }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($errors->any())
                        <p role="alert" class="text-sm font-semibold text-rust">{{ $errors->first() }}</p>
                    @endif
                </div>

                <div class="mt-[22px] flex flex-wrap gap-2.5">
                    <button type="submit" class="btn btn-dark min-h-[44px] px-5 text-[15px]">Registrar pedido</button>
                    <button type="button" class="btn btn-line min-h-[44px] px-5 text-[15px]" @click="registrar = false">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</x-painel-layout>
