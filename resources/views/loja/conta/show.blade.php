<x-loja-layout titulo="Minha conta">
    <x-loja.conta :titulo="'Olá, '.str($cliente['nome'])->before(' ').'.'">
        <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,.7fr)] lg:gap-8">
            <div class="card p-5 md:p-7">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="font-display text-[26px] font-bold">Últimos pedidos</h2>
                        <p class="mt-1 text-[15px] text-muted">
                            {{ $totalPedidos }} {{ Str::plural('pedido', $totalPedidos) }} · {{ $emAndamento }} em andamento
                        </p>
                    </div>
                    <a href="{{ route('cliente.pedidos.index') }}" class="inline-flex min-h-[44px] items-center gap-2 font-semibold">Ver todos <x-icone nome="seta" tamanho="18" /></a>
                </div>
                <p class="mt-3 text-sm text-muted">Quem registra o pedido é o vendedor, depois da conversa no WhatsApp. Exemplos fictícios.</p>
                <div class="mt-4 flex flex-col gap-2.5">
                    @foreach ($recentes as $pedido)
                        <x-loja.pedido-card :pedido="$pedido" />
                    @endforeach
                </div>
                <button type="button" class="btn btn-lime mt-5 w-full md:w-auto" @click="$store.whatsapp.abrir('Olá! Gostaria de falar sobre um pedido.')">
                    <x-icone nome="whatsapp" /> Falar com o vendedor
                </button>
            </div>

            <div class="flex flex-col gap-5 lg:gap-8">
                <div class="card p-5 md:p-7">
                    <h2 class="font-display text-[26px] font-bold">Meus dados</h2>
                    <dl class="mt-3.5 border-b border-line-soft">
                        <div class="border-t border-line-soft py-3"><dt class="text-[13px] text-muted">Nome</dt><dd class="font-semibold">{{ $cliente['nome'] }}</dd></div>
                        <div class="border-t border-line-soft py-3"><dt class="text-[13px] text-muted">E-mail</dt><dd class="break-all font-semibold">{{ $cliente['email'] }}</dd></div>
                        <div class="border-t border-line-soft py-3"><dt class="text-[13px] text-muted">WhatsApp</dt><dd class="font-semibold">{{ ($cliente['telefone'] ?? '') ?: 'Não informado' }}</dd></div>
                    </dl>
                    <a href="{{ route('cliente.perfil.edit') }}" class="btn btn-line mt-4 w-full">Editar perfil</a>
                </div>

                <a href="{{ route('cliente.pedidos.por_data') }}" class="flex items-center justify-between gap-4 rounded-card bg-ink p-5 text-snow transition hover:bg-ink-600 md:p-7">
                    <span>
                        <span class="block font-display text-[22px] font-bold">Pedidos por data</span>
                        <span class="mt-1 block text-sm text-fog">Veja o que você pediu em cada dia ou num período.</span>
                    </span>
                    <x-icone nome="seta" class="flex-none text-brand" />
                </a>
            </div>
        </div>
    </x-loja.conta>
</x-loja-layout>
