@use('App\Enums\Condicao')
@use('App\Enums\Disponibilidade')

@php
    $editando = $produto['slug'] !== null;
    $variacoes = old('variacoes', $produto['variacoes']);
@endphp

<x-painel-layout :titulo="$editando ? 'Editar produto' : 'Cadastrar produto'" secao="Produtos">
    <a href="{{ route('painel.produtos.index') }}" class="inline-flex min-h-[44px] items-center gap-2 font-semibold">
        <x-icone nome="voltar" tamanho="18" /> Voltar aos produtos
    </a>

    <form method="POST" action="{{ $editando ? route('painel.produtos.update', $produto['slug']) : route('painel.produtos.store') }}"
          class="mt-2 grid items-start gap-[18px] lg:grid-cols-[minmax(0,1.4fr)_minmax(0,.6fr)]"
          x-data="{ variacoes: @js(array_values($variacoes)), ativo: @js((bool) old('ativo', $produto['ativo'])) }">
        @csrf
        @if ($editando)
            @method('PUT')
        @endif

        <div class="flex flex-col gap-[18px]">
            @if ($errors->any())
                <div role="alert" class="rounded-card border border-rust bg-white p-4 text-sm font-semibold text-rust">{{ $errors->first() }}</div>
            @endif

            <section class="card p-6">
                <h2 class="font-display text-[22px] font-bold">Informações básicas</h2>
                <div class="mt-[18px] grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="nome" class="lbl text-[13px] text-body">Nome do produto</label>
                        <input id="nome" name="nome" type="text" value="{{ old('nome', $produto['nome']) }}" placeholder="Ex.: iPhone 17 Pro" class="inp min-h-[44px]">
                    </div>
                    <div>
                        <label for="categoria" class="lbl text-[13px] text-body">Categoria</label>
                        <select id="categoria" name="categoria" class="inp min-h-[44px]">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria['slug'] }}" @selected(old('categoria', $produto['categoria']) === $categoria['slug'])>{{ $categoria['nome'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="condicao" class="lbl text-[13px] text-body">Condição</label>
                        <select id="condicao" name="condicao" class="inp min-h-[44px]">
                            @foreach (Condicao::cases() as $condicao)
                                <option value="{{ $condicao->value }}" @selected(old('condicao', $produto['condicao']->value) === $condicao->value)>{{ $condicao->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="descricao" class="lbl text-[13px] text-body">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Texto curto e claro sobre o produto" class="inp resize-y pt-2.5">{{ old('descricao', $produto['descricao']) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="card p-6">
                <h2 class="font-display text-[22px] font-bold">Preço e disponibilidade</h2>
                <div class="mt-[18px] grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="preco" class="lbl text-[13px] text-body">Preço base (R$)</label>
                        <input id="preco" name="preco" type="number" min="0" inputmode="numeric" value="{{ old('preco', $produto['preco']) }}" placeholder="0" class="inp min-h-[44px]">
                    </div>
                    <div>
                        <label for="disponibilidade" class="lbl text-[13px] text-body">Disponibilidade</label>
                        <select id="disponibilidade" name="disponibilidade" class="inp min-h-[44px]">
                            @foreach (Disponibilidade::cases() as $disponibilidade)
                                <option value="{{ $disponibilidade->value }}" @selected(old('disponibilidade', $produto['disponibilidade']->value) === $disponibilidade->value)>{{ $disponibilidade->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <section class="card p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-display text-[22px] font-bold">Variações e estoque</h2>
                        <p class="mt-0.5 text-sm text-muted">Cor, armazenamento ou configuração, com preço adicional.</p>
                    </div>
                    <button type="button" class="btn btn-line btn-sm" @click="variacoes.push({ cor: '', opcao: '', adicional: 0, estoque: 0 })">Adicionar variação</button>
                </div>
                <div class="mt-3.5 overflow-x-auto">
                    <div class="min-w-[640px]">
                        <div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1fr)_120px_90px_90px] gap-2.5 py-2.5 text-xs font-semibold uppercase tracking-[.08em] text-muted">
                            <span>Cor</span><span>Armazenamento / config.</span><span>Adicional (R$)</span><span>Estoque</span><span></span>
                        </div>
                        <template x-for="(variacao, indice) in variacoes" :key="indice">
                            <div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1fr)_120px_90px_90px] items-center gap-2.5 border-t border-line-soft py-1.5">
                                <input type="text" aria-label="Cor" :name="`variacoes[${indice}][cor]`" x-model="variacao.cor" class="inp min-h-[44px]">
                                <input type="text" aria-label="Armazenamento ou configuração" :name="`variacoes[${indice}][opcao]`" x-model="variacao.opcao" class="inp min-h-[44px]">
                                <input type="number" aria-label="Preço adicional" :name="`variacoes[${indice}][adicional]`" x-model="variacao.adicional" class="inp min-h-[44px]">
                                <input type="number" min="0" aria-label="Estoque" :name="`variacoes[${indice}][estoque]`" x-model="variacao.estoque" class="inp min-h-[44px]">
                                <button type="button" class="btn btn-line btn-sm px-2.5" aria-label="Remover variação" @click="variacoes.splice(indice, 1)">Remover</button>
                            </div>
                        </template>
                        <p x-show="! variacoes.length" class="border-t border-line-soft py-4 text-sm text-muted">Nenhuma variação. O produto usa só o preço base.</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex flex-col gap-[18px]">
            <section class="card p-6">
                <h2 class="font-display text-[22px] font-bold">Imagens</h2>
                <div class="mt-3.5 grid grid-cols-3 gap-2">
                    <div class="rounded-[3px] bg-stage p-2"><x-produto-arte :forma="$produto['forma']" :cor="$produto['cores'][0]['hex']" /></div>
                    <div class="rounded-[3px] bg-stage p-2 opacity-60"><x-produto-arte :forma="$produto['forma']" :cor="$produto['cores'][0]['hex']" /></div>
                    <div class="grid min-h-[90px] place-items-center rounded-[3px] border border-dashed border-[#8C968E] p-1.5 text-center text-[13px] text-muted">+ Adicionar</div>
                </div>
                <p class="mt-2.5 text-[13px] text-muted">O envio e a ordenação de imagens ainda não funcionam neste protótipo.</p>
            </section>

            <section class="card p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-display text-[22px] font-bold">Visível na loja</h2>
                        <p class="text-sm text-muted">Produtos inativos ficam ocultos.</p>
                    </div>
                    <input type="hidden" name="ativo" :value="ativo ? 1 : 0">
                    <button type="button" role="switch" :aria-checked="ativo.toString()" aria-label="Produto ativo" @click="ativo = ! ativo">
                        <span class="relative inline-block h-[26px] w-11 flex-none rounded-full transition" :class="ativo ? 'bg-ink' : 'bg-fog'">
                            <i class="absolute top-[3px] size-5 rounded-full bg-white transition-all" :class="ativo ? 'left-[21px]' : 'left-[3px]'"></i>
                        </span>
                    </button>
                </div>
            </section>

            <section class="card flex flex-col gap-2.5 p-6">
                <button type="submit" class="btn btn-dark min-h-[44px] w-full text-[15px]">Salvar produto</button>
                <a href="{{ route('painel.produtos.index') }}" class="btn btn-line min-h-[44px] w-full text-[15px]">Cancelar</a>
                @if ($editando)
                    <div x-data="{ confirmar: false }">
                        <button type="button" x-show="! confirmar" @click="confirmar = true" class="btn btn-line min-h-[44px] w-full border-rust text-[15px] text-rust">Remover produto</button>
                        <button type="submit" x-show="confirmar" x-cloak form="remover-produto" class="btn btn-line min-h-[44px] w-full border-rust text-[15px] text-rust">Confirmar remoção</button>
                    </div>
                @endif
            </section>
        </div>
    </form>

    @if ($editando)
        <form id="remover-produto" method="POST" action="{{ route('painel.produtos.destroy', $produto['slug']) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-painel-layout>
