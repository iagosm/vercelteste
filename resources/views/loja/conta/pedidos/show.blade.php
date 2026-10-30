@use('App\Enums\StatusPedido')

<x-loja-layout :titulo="'Pedido #'.$pedido['id']">
    <x-loja.conta :titulo="'Pedido #'.$pedido['id']">
        <a href="{{ route('cliente.pedidos.index') }}" class="inline-flex min-h-[44px] items-center gap-2 font-semibold">
            <x-icone nome="voltar" tamanho="18" /> Voltar aos pedidos
        </a>

        <div class="mt-2 grid items-start gap-5 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,.8fr)] lg:gap-8">
            <div class="flex flex-col gap-5">
                <section class="card p-5 md:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="font-display text-xl font-bold">Andamento</h2>
                        <x-status-pedido :status="$pedido['status']" />
                    </div>
                    <p class="mt-2 text-[15px] text-body">{{ $pedido['status']->descricaoCliente() }}</p>
                    @if ($pedido['status'] !== StatusPedido::Cancelado)
                        <x-pedido-andamento :pedido="$pedido" class="mt-4" />
                    @endif
                </section>

                <section class="card p-5 md:p-6">
                    <h2 class="font-display text-xl font-bold">Detalhes</h2>
                    <dl class="mt-3 border-b border-line-soft">
                        @foreach ([
                            'Produto' => $pedido['produto'],
                            'Valor' => Number::currency($pedido['valor'], precision: 0),
                            'Data' => $pedido['registrado_em']->format('d/m/Y'),
                            'Pagamento' => $pedido['pagamento'],
                            'Entrega' => $pedido['entrega'],
                        ] as $rotulo => $valor)
                            <div class="flex flex-col gap-0.5 border-t border-line-soft py-3 md:grid md:grid-cols-[150px_minmax(0,1fr)] md:gap-3">
                                <dt class="text-[13px] text-muted md:text-base">{{ $rotulo }}</dt>
                                <dd class="font-semibold md:font-normal">{{ $valor }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <p class="mt-3 text-[13px] text-muted">Pagamento e entrega são combinados com o vendedor no WhatsApp.</p>
                </section>

                <button type="button" class="btn btn-lime w-full md:w-auto md:self-start"
                        @click="$store.whatsapp.abrir(@js('Olá! Gostaria de falar sobre o meu pedido #'.$pedido['id'].' ('.$pedido['produto'].').'))">
                    <x-icone nome="whatsapp" /> Falar sobre este pedido
                </button>
            </div>

            <section class="card p-5 md:p-6">
                <h2 class="font-display text-xl font-bold">Histórico</h2>
                <x-pedido-historico :pedido="$pedido" :responsavel="false" class="mt-3.5" />
            </section>
        </div>
    </x-loja.conta>
</x-loja-layout>
