<x-painel-layout titulo="Visão geral" secao="Início">
    {{-- Indicadores --}}
    <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-[repeat(auto-fit,minmax(min(190px,100%),1fr))]">
        @foreach ($indicadores as $indicador)
            <a href="{{ $indicador['url'] }}"
               class="flex flex-col gap-1 rounded-card border px-[18px] pb-4 pt-[18px] text-ink transition hover:border-ink {{ $indicador['destaque'] ? 'border-brand bg-brand' : 'border-line bg-white' }}">
                <span class="text-[13px] {{ $indicador['destaque'] ? 'text-[#1E2D1A]' : 'text-muted' }}">{{ $indicador['label'] }}</span>
                <span class="font-display text-[40px] font-bold leading-[1.05]">{{ $indicador['valor'] }}</span>
                <span class="text-[12.5px] {{ $indicador['destaque'] ? 'text-[#1E2D1A]' : 'text-muted' }}">{{ $indicador['dica'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="mt-[18px] grid items-start gap-[18px] lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)]">
        <section class="card p-[22px]">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="font-display text-[22px] font-bold">Precisam de atenção</h2>
                <a href="{{ route('painel.pedidos.index') }}" class="btn btn-line btn-sm">Ver todos os pedidos</a>
            </div>
            @forelse ($atencao as $pedido)
                <a href="{{ route('painel.pedidos.show', $pedido['id']) }}" class="grid min-h-14 w-full grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3.5 gap-y-1.5 border-t border-line-soft py-3.5">
                    <span>
                        <span class="font-semibold">#{{ $pedido['id'] }} · {{ $pedido['cliente'] }}</span><br>
                        <span class="text-sm text-muted">{{ $pedido['produto'] }}</span>
                    </span>
                    <span class="text-right">
                        <x-status-pedido :status="$pedido['status']" /><br>
                        <span class="text-[13px] font-semibold text-rust">{{ $pedido['motivo'] }}</span>
                    </span>
                </a>
            @empty
                <p class="py-3 text-muted">Nada parado por aqui.</p>
            @endforelse
        </section>

        <section class="card p-[22px]">
            <h2 class="mb-4 font-display text-[22px] font-bold">Atividade recente</h2>
            <ul>
                @foreach ($atividades as $atividade)
                    <li class="grid grid-cols-[10px_minmax(0,1fr)] gap-3 border-t border-line-soft py-2.5 text-[14.5px]">
                        <span class="mt-[7px] size-[9px] rounded-full" style="background: {{ $atividade['status']->corBarra() }}"></span>
                        <span>
                            <span class="font-semibold">{{ $atividade['texto'] }}</span><br>
                            <span class="text-[13px] text-muted">{{ $atividade['em']->format('d/m H:i') }} · {{ $atividade['por'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    {{-- Quadro por status --}}
    <section class="card mt-[18px] p-[22px]">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="font-display text-[22px] font-bold">Situação dos pedidos</h2>
                <p class="mt-0.5 text-sm text-muted">Cada coluna é uma etapa. Abra um pedido ou use Avançar para passar para a próxima.</p>
            </div>
            <a href="{{ route('painel.pedidos.index') }}" class="btn btn-line btn-sm">Ver em lista</a>
        </div>
        <div role="img" aria-label="Distribuição dos pedidos por status" class="mt-4 flex h-2.5 overflow-hidden rounded-[3px] bg-line-soft">
            @foreach ($colunas->where('total', '>', 0) as $coluna)
                <span style="flex: {{ $coluna['total'] }}; background: {{ $coluna['status']->corBarra() }}"></span>
            @endforeach
        </div>
        <div class="mt-3.5 grid grid-cols-[repeat(6,220px)] gap-2.5 overflow-x-auto lg:grid-cols-[repeat(6,minmax(170px,1fr))]">
            @foreach ($colunas as $coluna)
                <section aria-label="{{ $coluna['status']->label() }}" class="flex min-w-0 flex-col gap-2 rounded-card bg-paper p-2.5">
                    <div class="flex items-center justify-between gap-1.5">
                        <x-status-pedido :status="$coluna['status']" class="px-[9px] py-[3px] text-[12.5px]" />
                        <b>{{ $coluna['total'] }}</b>
                    </div>
                    <p class="text-[12.5px] text-muted">{{ $coluna['total'] ? Number::currency($coluna['soma'], precision: 0) : '—' }}</p>
                    <ul class="flex flex-col gap-2">
                        @foreach ($coluna['pedidos'] as $pedido)
                            <li class="flex flex-col rounded-card border border-line bg-white">
                                <a href="{{ route('painel.pedidos.show', $pedido['id']) }}" class="block min-h-[44px] w-full px-2.5 pb-2 pt-2.5">
                                    <span class="text-sm font-semibold">#{{ $pedido['id'] }} · {{ $pedido['cliente'] }}</span>
                                    <span class="mt-0.5 block text-[13px] text-muted">{{ Str::limit($pedido['produto'], 36) }}</span>
                                    <span class="mt-1 block text-[12.5px] font-semibold {{ $pedido['atrasado'] ? 'text-rust' : 'text-muted' }}">{{ $pedido['idade'] }}</span>
                                </a>
                                @if ($proximo = $coluna['status']->proximo())
                                    <form method="POST" action="{{ route('painel.pedidos.update', $pedido['id']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $proximo->value }}">
                                        <button type="submit" aria-label="Avançar pedido #{{ $pedido['id'] }} para {{ $proximo->label() }}"
                                                class="flex min-h-[44px] w-full items-center justify-between gap-1.5 border-t border-line-soft px-2.5 text-[13px] font-semibold">
                                            Avançar <x-icone nome="seta" tamanho="16" />
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if ($coluna['total'] === 0)
                        <p class="px-0.5 py-2 text-[13px] text-muted">Nenhum pedido.</p>
                    @endif
                </section>
            @endforeach
        </div>
    </section>

    <div class="mt-[18px] grid items-start gap-[18px] lg:grid-cols-3">
        <section class="card p-[22px]">
            <h2 class="font-display text-[22px] font-bold">Valores dos pedidos</h2>
            <p class="mt-0.5 text-[13px] text-muted">Soma dos valores estimados (fictícios).</p>
            <dl class="mt-3">
                @foreach ($valores as $valor)
                    <div class="flex items-baseline justify-between gap-3 border-t border-line-soft py-[11px]">
                        <dt class="text-[14.5px]"><span class="mr-2 inline-block size-2.5 rounded-sm" style="background: {{ $valor['cor'] }}"></span>{{ $valor['label'] }}</dt>
                        <dd class="font-display text-xl font-bold">{{ $valor['valor'] === null ? '—' : Number::currency($valor['valor'], precision: 0) }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="card p-[22px]">
            <h2 class="font-display text-[22px] font-bold">De onde vêm os pedidos</h2>
            <ul class="mt-3.5 flex flex-col gap-3.5">
                @foreach ($origens as $origem)
                    <li>
                        <div class="flex justify-between gap-3 text-[14.5px]"><span>{{ $origem['label'] }}</span><b>{{ $origem['total'] }}</b></div>
                        <div class="mt-1.5 h-2 rounded-sm bg-line-soft"><div class="h-2 rounded-sm bg-ink" style="width: {{ $origem['percentual'] }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="card p-[22px]">
            <h2 class="font-display text-[22px] font-bold">Estoque baixo</h2>
            <p class="mt-0.5 text-[13px] text-muted">Produtos com 1 unidade ou menos.</p>
            <ul class="mt-2">
                @forelse ($estoqueBaixo as $produto)
                    <li class="border-t border-line-soft">
                        <a href="{{ route('painel.produtos.edit', $produto['slug']) }}" class="flex min-h-[52px] w-full items-center justify-between gap-2.5">
                            <span class="text-[14.5px] font-semibold">{{ $produto['nome'] }}</span>
                            <span class="tag {{ $produto['estoque'] === 0 ? 'bg-[#E6E8E2] text-muted' : 'bg-[#FFE9B0] text-[#5C4300]' }}">{{ $produto['estoque'] === 0 ? 'Sem estoque' : '1 unidade' }}</span>
                        </a>
                    </li>
                @empty
                    <li class="pt-2 text-muted">Tudo com estoque.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <section class="card mt-[18px] p-[22px]">
        <h2 class="font-display text-[22px] font-bold">Como um pedido nasce</h2>
        <p class="mt-1.5 max-w-[720px] text-muted">Clicar em comprar no site abre uma conversa. Ele não cria o pedido sozinho: o vendedor registra aqui. O que é manual hoje e o que poderia ser automatizado no futuro:</p>
        <ol class="mt-[18px] grid gap-3.5 lg:grid-cols-4">
            @foreach ([
                ['Cliente clica em comprar', 'O site abre o WhatsApp com a mensagem do produto já escrita.', 'Automático hoje', true],
                ['Conversa com o vendedor', 'Dúvidas, disponibilidade e condições são tratadas no WhatsApp.', 'Manual', false],
                ['Vendedor registra o pedido', 'Aqui no painel, ligando cliente, produto e configuração.', 'Manual · pode automatizar', false],
                ['Atualiza o status', 'Do Novo ao Concluído, com histórico de cada mudança.', 'Manual · avisos no futuro', false],
            ] as [$etapa, $descricao, $tipo, $automatico])
                <li class="flex flex-col gap-2 rounded-card border border-line p-4">
                    <span class="font-display font-bold text-moss">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="font-semibold">{{ $etapa }}</span>
                    <span class="text-sm text-muted">{{ $descricao }}</span>
                    <span class="tag self-start {{ $automatico ? 'bg-[#CFEBD6] text-[#12482B]' : 'bg-[#FFE9B0] text-[#5C4300]' }}">{{ $tipo }}</span>
                </li>
            @endforeach
        </ol>
    </section>
</x-painel-layout>
