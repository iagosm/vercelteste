{{-- Prévia da conversa. Abra com: $store.whatsapp.abrir('mensagem') --}}
<div x-show="$store.whatsapp.aberto" x-cloak class="fixed inset-0 z-[60] grid place-items-center p-4"
     @keydown.escape.window="$store.whatsapp.fechar()">
    <button type="button" aria-label="Fechar" class="absolute inset-0 bg-[rgba(5,9,6,.78)]" @click="$store.whatsapp.fechar()"></button>

    <div role="dialog" aria-modal="true" aria-label="Prévia da conversa no WhatsApp"
         class="relative grid max-h-[calc(100vh-32px)] w-full max-w-[880px] animate-rise overflow-auto rounded-card border border-ink-300 bg-ink text-snow md:grid-cols-[minmax(0,.9fr)_minmax(0,1.1fr)]">
        <div class="flex flex-col gap-[18px] px-8 py-9">
            <p class="eyebrow text-brand">Quase lá</p>
            <h2 class="font-display text-[34px] font-bold leading-[1.02]">Você vai conversar com um vendedor.</h2>
            <p class="text-fog">Ao abrir o WhatsApp, esta mensagem já vai preenchida. Basta enviar.</p>
            <a :href="$store.whatsapp.link" target="_blank" rel="noopener" class="btn btn-lime mt-2 min-h-[56px]">
                <x-icone nome="whatsapp" />
                Abrir WhatsApp
            </a>
            <button type="button" class="btn btn-ghost min-h-[48px]" @click="$store.whatsapp.fechar()">Voltar à loja</button>
            <p class="text-xs leading-normal text-fog">
                Abrir a conversa não cria pedido: o vendedor registra no painel.
                @if (config('loja.whatsapp') === '00000000000')
                    Link de demonstração com número placeholder (00000000000). Configure LOJA_WHATSAPP no .env.
                @endif
            </p>
        </div>

        <div class="flex flex-col border-t border-ink-500 bg-ink-800 md:min-h-[380px] md:border-l md:border-t-0">
            <div class="flex items-center gap-3 border-b border-ink-500 bg-ink-700 px-5 py-3.5">
                <span class="grid size-10 place-items-center rounded-full bg-brand font-display text-lg font-extrabold text-ink">{{ mb_substr(config('loja.nome'), 0, 1) }}</span>
                <div>
                    <p class="font-semibold leading-tight">{{ config('loja.nome') }} · Vendas</p>
                    <p class="text-xs text-fog">{{ config('loja.whatsapp_exibicao') }}</p>
                </div>
            </div>
            <div class="flex flex-1 flex-col justify-end px-5 py-6">
                <p class="self-center rounded-full bg-ink-700 px-3 py-1 text-xs text-fog">Prévia da mensagem</p>
            </div>
            <div class="border-t border-ink-500 px-4 pb-[18px] pt-3.5">
                <div class="animate-rise-late rounded-[14px_14px_4px_14px] border border-[#2B5A38] bg-[#1D3A25] px-4 py-3.5 text-[15px] leading-normal" x-text="$store.whatsapp.mensagem"></div>
            </div>
        </div>

        <button type="button" aria-label="Fechar janela" @click="$store.whatsapp.fechar()"
                class="absolute right-3 top-3 grid size-11 place-items-center rounded-full border border-ink-300 bg-ink-700">
            <x-icone nome="fechar" tamanho="18" />
        </button>
    </div>
</div>
