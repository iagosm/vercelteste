<x-painel-layout titulo="Configurações" secao="Sistema">
    <div class="grid items-start gap-[18px] lg:grid-cols-2">
        <section class="card p-6">
            <h2 class="font-display text-[22px] font-bold">Dados da loja</h2>
            <form method="POST" action="{{ route('painel.configuracoes.update') }}" class="mt-[18px] flex flex-col gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="nome" class="lbl text-[13px] text-body">Nome da loja</label>
                    <input id="nome" name="nome" type="text" value="{{ old('nome', $configuracao['nome']) }}" class="inp min-h-[44px]">
                </div>
                <div>
                    <label for="whatsapp" class="lbl text-[13px] text-body">Número do WhatsApp (usado nos botões do site)</label>
                    <input id="whatsapp" name="whatsapp" type="text" inputmode="tel" value="{{ old('whatsapp', $configuracao['whatsapp']) }}" placeholder="[NÚMERO COM DDI E DDD]" class="inp min-h-[44px]">
                </div>
                <div>
                    <label for="horario" class="lbl text-[13px] text-body">Horário de atendimento</label>
                    <input id="horario" name="horario" type="text" value="{{ old('horario', $configuracao['horario']) }}" placeholder="[DIAS E HORÁRIOS]" class="inp min-h-[44px]">
                </div>
                @if ($errors->any())
                    <p role="alert" class="text-sm font-semibold text-rust">{{ $errors->first() }}</p>
                @endif
                <button type="submit" class="btn btn-dark min-h-[44px] self-start px-5 text-[15px]">Salvar</button>
            </form>
        </section>

        <div class="flex flex-col gap-[18px]">
            <section class="card p-6">
                <h2 class="font-display text-[22px] font-bold">Estados do pedido</h2>
                <ul class="mt-3">
                    @foreach ($status as $estado)
                        <li class="flex items-center justify-between gap-3 border-t border-line-soft py-2.5">
                            <x-status-pedido :status="$estado" />
                            <span class="text-right text-[13px] text-muted">{{ $estado->descricao() }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="card p-6">
                <h2 class="font-display text-[22px] font-bold">Integrações</h2>
                <ul class="mt-3">
                    <li class="flex items-center justify-between gap-3 border-t border-line-soft py-3">
                        <span>WhatsApp Business API<br><span class="text-[13px] text-muted">Criar rascunho de pedido a partir da conversa</span></span>
                        <span class="tag bg-[#ECEDEA] text-muted">Futuro</span>
                    </li>
                    <li class="flex items-center justify-between gap-3 border-t border-line-soft py-3">
                        <span>Pagamento online<br><span class="text-[13px] text-muted">Fora do escopo desta versão</span></span>
                        <span class="tag bg-[#ECEDEA] text-muted">Não previsto</span>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</x-painel-layout>
