@use('App\Enums\StatusPedido')


<x-painel-layout :titulo="'Pedido #'.$pedido['id']" secao="Pedidos">
    <a href="{{ route('painel.pedidos.index') }}" class="inline-flex min-h-[44px] items-center gap-2 font-semibold">
        <x-icone nome="voltar" tamanho="18" /> Voltar aos pedidos
    </a>

    <div class="mt-2 grid items-start gap-[18px] lg:grid-cols-[minmax(0,1.2fr)_minmax(0,.8fr)]">
        <div class="flex flex-col gap-[18px]">
            <section class="card px-6 py-[22px]">
                <h3 class="font-display text-lg font-bold">Andamento</h3>
                <x-pedido-andamento :pedido="$pedido" class="mt-3.5">
                    @if ($pedido['status'] === StatusPedido::Cancelado)
                        <p class="mt-3 text-sm text-muted">Este pedido foi cancelado. Se o cliente voltar, registre um novo pedido.</p>
                    @endif
                </x-pedido-andamento>
            </section>

            <section class="card p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="font-display text-[28px] font-bold">#{{ $pedido['id'] }}</h2>
                    <x-status-pedido :status="$pedido['status']" />
                </div>
                <dl class="mt-[18px] border-b border-line-soft">
                    <div class="grid grid-cols-[150px_minmax(0,1fr)] items-center gap-3 border-t border-line-soft py-3">
                        <dt class="text-muted">Cliente</dt>
                        <dd><a href="{{ route('painel.clientes.show', $cliente['id']) }}" class="inline-flex min-h-[44px] items-center font-semibold underline">{{ $cliente['nome'] }}</a></dd>
                    </div>
                    <div class="grid grid-cols-[150px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Produto</dt><dd>{{ $pedido['produto'] }}</dd></div>
                    <div class="grid grid-cols-[150px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Valor estimado</dt><dd>{{ Number::currency($pedido['valor'], precision: 0) }} <span class="text-[13px] text-muted">· demonstrativo</span></dd></div>
                    <div class="grid grid-cols-[150px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Origem</dt><dd>{{ $pedido['origem'] }}</dd></div>
                    <div class="grid grid-cols-[150px_minmax(0,1fr)] gap-3 border-t border-line-soft py-3"><dt class="text-muted">Registrado em</dt><dd>{{ $pedido['registrado_em']->format('d/m H:i') }}</dd></div>
                </dl>
                <a href="{{ $linkWhatsApp }}" target="_blank" rel="noopener" class="btn btn-lime mt-5 min-h-[44px] px-5 text-[15px]">
                    <x-icone nome="whatsapp" tamanho="18" /> Abrir conversa no WhatsApp
                </a>
                <p class="mt-2.5 text-[13px] text-muted">O link abre uma conversa com o número do cliente (fictício). O painel não lê nem sincroniza mensagens.</p>
            </section>

            <section class="card p-6">
                <h3 class="font-display text-xl font-bold">Mudar status</h3>
                <p class="mt-1 text-sm text-muted">Próximo passo sugerido: <b>{{ $pedido['status']->proximoPasso() }}</b></p>
                <div class="mt-3.5 flex flex-wrap gap-2">
                    @foreach (StatusPedido::cases() as $status)
                        <form method="POST" action="{{ route('painel.pedidos.update', $pedido['id']) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $status->value }}">
                            <button type="submit" class="chip text-sm {{ $pedido['status'] === $status ? 'chip-on' : '' }}" @if ($pedido['status'] === $status) aria-pressed="true" @endif>{{ $status->label() }}</button>
                        </form>
                    @endforeach
                </div>
            </section>

            <section class="card p-6">
                <h3 class="font-display text-xl font-bold">Observações internas</h3>
                <p class="mt-1 text-sm text-muted">Só a equipe vê. Combinados de pagamento, prazo e entrega.</p>
                <form method="POST" action="{{ route('painel.pedidos.update', $pedido['id']) }}">
                    @csrf
                    @method('PATCH')
                    <label for="observacao" class="sr-only">Observações do pedido</label>
                    <textarea id="observacao" name="observacao" rows="3" placeholder="Ex.: cliente paga no Pix; retira na loja sexta" class="inp mt-3 resize-y pt-2.5">{{ old('observacao', $pedido['observacao']) }}</textarea>
                    <button type="submit" class="btn btn-dark btn-sm mt-2.5">Salvar observação</button>
                </form>
            </section>
        </div>

        <section class="card p-6">
            <h3 class="font-display text-xl font-bold">Histórico</h3>
            <x-pedido-historico :pedido="$pedido" class="mt-3.5" />
        </section>
    </div>
</x-painel-layout>
