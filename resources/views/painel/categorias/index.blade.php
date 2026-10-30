<x-painel-layout titulo="Categorias" secao="Catálogo">
    <div class="max-w-[760px]">
        <div class="card py-2">
            @foreach ($categorias as $categoria)
                <div class="grid grid-cols-[minmax(0,1fr)_110px_70px_100px] items-center gap-3.5 border-t border-line-soft px-5 py-3 first:border-t-0">
                    <span class="font-semibold">{{ $categoria['nome'] }}</span>
                    <span class="text-muted">{{ $categoria['total_produtos'] }} {{ $categoria['total_produtos'] === 1 ? 'produto' : 'produtos' }}</span>
                    <form method="POST" action="{{ route('painel.categorias.update', $categoria['slug']) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="ativa" value="{{ $categoria['ativa'] ? 0 : 1 }}">
                        <button type="submit" role="switch" aria-checked="{{ $categoria['ativa'] ? 'true' : 'false' }}" aria-label="Exibir {{ $categoria['nome'] }} na loja">
                            <x-painel.switch :ligado="$categoria['ativa']" />
                        </button>
                    </form>
                    <form method="POST" action="{{ route('painel.categorias.destroy', $categoria['slug']) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-line btn-sm {{ $categoria['total_produtos'] ? 'opacity-40' : '' }}" aria-label="Remover {{ $categoria['nome'] }}">Remover</button>
                    </form>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('painel.categorias.store') }}" class="mt-4 flex flex-wrap gap-2">
            @csrf
            <label for="nova-categoria" class="sr-only">Nova categoria</label>
            <input id="nova-categoria" name="nome" type="text" value="{{ old('nome') }}" placeholder="Nome da nova categoria" class="inp min-h-[44px] max-w-[360px] flex-[1_1_240px]">
            <button type="submit" class="btn btn-dark min-h-[44px] px-5 text-[15px]">Adicionar categoria</button>
        </form>
        @error('nome')
            <p role="alert" class="mt-2 text-sm font-semibold text-rust">{{ $message }}</p>
        @enderror
        <p class="mt-2.5 text-[13px] text-muted">Só é possível remover categorias sem produtos.</p>
    </div>
</x-painel-layout>
