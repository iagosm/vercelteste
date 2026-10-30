<x-painel-layout :titulo="$cliente['nome']" secao="Clientes">
    <a href="{{ route('painel.clientes.index') }}" class="inline-flex min-h-[44px] items-center gap-2 font-semibold">
        <x-icone nome="voltar" tamanho="18" /> Voltar aos clientes
    </a>

    <div class="mt-2 grid items-start gap-[18px] lg:grid-cols-[minmax(0,.8fr)_minmax(0,1.2fr)]">
        <section class="card p-6">
            <span class="grid size-14 place-items-center rounded-full bg-ink font-display text-[22px] font-bold text-brand">{{ mb_substr($cliente['nome'], 0, 1) }}</span>
            <h2 class="mt-3.5 font-display text-[28px] font-bold">{{ $cliente['nome'] }}</h2>
            <dl class="mt-3.5 border-b border-line-soft">
                <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Telefone</dt><dd>{{ $cliente['telefone'] }}</dd></div>
                <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">E-mail</dt><dd class="break-all">{{ $cliente['email'] }}</dd></div>
                <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Cliente desde</dt><dd>{{ $cliente['desde'] }}</dd></div>
                <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Pedidos</dt><dd>{{ $pedidos->count() }}</dd></div>
            </dl>
            <a href="{{ $linkWhatsApp }}" target="_blank" rel="noopener" class="btn btn-lime mt-[18px] min-h-[44px] px-5 text-[15px]">
                <x-icone nome="whatsapp" tamanho="18" /> Abrir WhatsApp
            </a>
        </section>

        <div class="flex flex-col gap-[18px]">
            <section class="card p-6">
                <h3 class="font-display text-xl font-bold">Pedidos associados</h3>
                <ul class="mt-2.5">
                    @forelse ($pedidos as $pedido)
                        <li class="border-t border-line-soft">
                            <a href="{{ route('painel.pedidos.show', $pedido['id']) }}" class="grid min-h-14 w-full grid-cols-[70px_minmax(0,1fr)_auto] items-center gap-3 py-2">
                                <b>#{{ $pedido['id'] }}</b>
                                <span>{{ $pedido['produto'] }}</span>
                                <x-status-pedido :status="$pedido['status']" />
                            </a>
                        </li>
                    @empty
                        <li class="pt-2 text-muted">Sem pedidos registrados.</li>
                    @endforelse
                </ul>
            </section>

            <section class="card p-6">
                <h3 class="font-display text-xl font-bold">Histórico de atendimento</h3>
                <ol class="mt-3">
                    @foreach ($cliente['anotacoes'] as $anotacao)
                        <li class="grid grid-cols-[64px_minmax(0,1fr)] gap-3 border-t border-line-soft py-2.5">
                            <span class="text-[13px] text-muted">{{ $anotacao['data'] }}</span>
                            <span>{{ $anotacao['texto'] }}</span>
                        </li>
                    @endforeach
                </ol>
                <form method="POST" action="{{ route('painel.clientes.anotacoes.store', $cliente['id']) }}" class="mt-3 flex flex-wrap gap-2">
                    @csrf
                    <label for="nova-anotacao" class="sr-only">Nova anotação</label>
                    <input id="nova-anotacao" name="texto" type="text" placeholder="Anotar uma conversa ou preferência" class="inp min-h-[44px] flex-[1_1_220px]">
                    <button type="submit" class="btn btn-dark min-h-[44px] px-5 text-[15px]">Adicionar</button>
                </form>
                @error('texto')
                    <p role="alert" class="mt-2 text-sm font-semibold text-rust">{{ $message }}</p>
                @enderror
            </section>
        </div>
    </div>
</x-painel-layout>
