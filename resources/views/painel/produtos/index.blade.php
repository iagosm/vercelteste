@use('App\Support\DemoData')

@php($colunas = 'grid-template-columns: 56px minmax(200px,1.6fr) 120px 100px 120px 130px 90px 90px')

<x-painel-layout titulo="Produtos" secao="Catálogo">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('painel.produtos.index') }}" class="flex flex-[1_1_320px] flex-wrap gap-2.5" @change="$el.submit()">
            <div class="relative max-w-[340px] flex-[1_1_220px]">
                <label for="busca-produtos" class="sr-only">Buscar produtos</label>
                <x-icone nome="busca" tamanho="18" class="absolute left-3.5 top-[13px] text-muted" />
                <input id="busca-produtos" name="q" type="search" value="{{ request('q') }}" placeholder="Buscar produto" class="inp min-h-[44px] pl-10">
            </div>
            <div>
                <label for="filtro-categoria" class="sr-only">Categoria</label>
                <select id="filtro-categoria" name="categoria" class="inp min-h-[44px] min-w-[170px]">
                    <option value="">Todas as categorias</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria['slug'] }}" @selected($categoriaAtual === $categoria['slug'])>{{ $categoria['nome'] }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <a href="{{ route('painel.produtos.create') }}" class="btn btn-dark min-h-[44px] px-5 text-[15px]">
            <x-icone nome="mais" tamanho="18" /> Cadastrar produto
        </a>
    </div>

    <div class="card mt-4 overflow-x-auto">
        <div class="min-w-[980px]">
            <div class="table-head" role="row" style="{{ $colunas }}">
                <span></span><span>Produto</span><span>Categoria</span><span>Condição</span><span>Preço</span><span>Estoque</span><span>Ativo</span><span></span>
            </div>
            @forelse ($produtos as $produto)
                @php($estoque = $produto['estoque'])
                <div class="table-row" role="row" style="{{ $colunas }}">
                    <span class="block w-11 rounded-[3px] bg-stage px-[5px] py-[3px]">
                        <x-produto-arte :forma="$produto['forma']" :cor="$produto['cores'][0]['hex']" />
                    </span>
                    <span>
                        <span class="font-semibold">{{ $produto['nome'] }}</span><br>
                        <span class="text-[13px] text-muted">{{ count($produto['variacoes']) }} {{ count($produto['variacoes']) === 1 ? 'variação' : 'variações' }}</span>
                    </span>
                    <span>{{ DemoData::categoria($produto['categoria'])['nome'] ?? '' }}</span>
                    <span>{{ $produto['condicao']->label() }}</span>
                    <span>{{ Number::currency($produto['preco'], precision: 0) }}</span>
                    <span>
                        <span class="tag {{ $estoque === 0 ? 'bg-[#E6E8E2] text-muted' : ($estoque <= 1 ? 'bg-[#FFE9B0] text-[#5C4300]' : 'bg-[#CFEBD6] text-[#12482B]') }}">
                            {{ $estoque === 0 ? 'Sem estoque' : $estoque.' '.($estoque === 1 ? 'unidade' : 'unidades') }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('painel.produtos.update', $produto['slug']) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="somente_ativo" value="1">
                        <input type="hidden" name="ativo" value="{{ $produto['ativo'] ? 0 : 1 }}">
                        <button type="submit" role="switch" aria-checked="{{ $produto['ativo'] ? 'true' : 'false' }}" aria-label="Exibir {{ $produto['nome'] }} na loja">
                            <x-painel.switch :ligado="$produto['ativo']" />
                        </button>
                    </form>
                    <a href="{{ route('painel.produtos.edit', $produto['slug']) }}" class="btn btn-line btn-sm" aria-label="Editar {{ $produto['nome'] }}">Editar</a>
                </div>
            @empty
                <p class="px-5 py-8 text-muted">Nenhum produto encontrado.</p>
            @endforelse
        </div>
    </div>
</x-painel-layout>
