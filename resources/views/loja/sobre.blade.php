<x-loja-layout titulo="Sobre nós">
    <section class="bg-ink text-snow">
        <div class="wrap py-14 md:pb-24 md:pt-[88px]">
            <p class="eyebrow text-brand"><span class="h-px w-8 bg-brand"></span>Sobre nós</p>
            <h1 class="mt-6 max-w-[900px] font-display text-[clamp(42px,6vw,88px)] font-extrabold leading-[.98]">
                Uma loja pequena, com atendimento de <span class="text-brand">gente.</span>
            </h1>
            <p class="mt-[26px] max-w-[560px] text-[19px] text-fog">Aqui você não fala com um robô nem com um formulário. Fala com uma pessoa que conhece o produto e quer que você escolha bem.</p>
            <p class="mt-3.5 text-[13px] text-fog">Textos de exemplo. Substitua pelas informações reais da loja.</p>
        </div>
    </section>

    <section class="py-14 md:pb-[88px] md:pt-[104px]">
        <div class="wrap grid items-center gap-10 lg:grid-cols-2 lg:gap-[72px]">
            <div class="placeholder-photo aspect-[4/3]"><x-icone nome="camera" tamanho="34" traco="1.6" />[Foto da fachada ou do balcão]</div>
            <div>
                <p class="eyebrow text-moss">Nossa história</p>
                <h2 class="mt-3.5 font-display text-[clamp(30px,3.8vw,48px)] font-bold leading-none">[Como a loja começou]</h2>
                <p class="mt-5 text-[17px] leading-[1.65] text-body">[Conte em duas ou três frases como a loja nasceu, quem começou e por que decidiu vender produtos Apple. Quanto mais específico e honesto, melhor.]</p>
                <p class="mt-3.5 text-[17px] leading-[1.65] text-body">[Se houver loja física, cidade de atuação ou tempo de mercado, este é o lugar para dizer.]</p>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-white">
        <div class="wrap py-14 md:pb-24 md:pt-[88px]">
            <h2 class="max-w-[640px] font-display text-[clamp(30px,3.8vw,48px)] font-bold leading-none">Como a gente trabalha</h2>
            <div class="mt-12 grid gap-12 lg:grid-cols-3">
                @foreach ([
                    ['Falamos claro', 'Sem jargão e sem letras miúdas. Se você não precisa do modelo mais caro, a gente diz.'],
                    ['Você decide no seu tempo', 'Pode perguntar, comparar e voltar outro dia. Sem pressão para fechar.'],
                    ['A conversa continua depois', 'Se surgir uma dúvida de uso, o mesmo WhatsApp está lá.'],
                ] as [$principio, $explicacao])
                    <div class="border-t-2 border-ink pt-5">
                        <span class="font-display font-bold text-moss">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-1.5 font-display text-2xl font-bold">{{ $principio }}</h3>
                        <p class="mt-2 text-muted">{{ $explicacao }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-7 text-[13px] text-muted">Texto de exemplo. Ajuste aos princípios reais da loja.</p>
        </div>
    </section>

    <section class="py-14 md:pb-[104px] md:pt-24">
        <div class="wrap">
            <h2 class="font-display text-[clamp(30px,3.8vw,48px)] font-bold leading-none">Quem atende você</h2>
            <div class="mt-10 grid grid-cols-2 gap-[18px] lg:grid-cols-4">
                @foreach (range(1, 4) as $pessoa)
                    <div>
                        <div class="placeholder-photo aspect-square"><x-icone nome="pessoa" tamanho="40" traco="1.4" /></div>
                        <p class="mt-3 font-semibold">[Nome]</p>
                        <p class="text-sm text-muted">[Função]</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-brand">
        <div class="wrap flex flex-wrap items-center justify-between gap-7 py-[68px]">
            <h2 class="max-w-[560px] font-display text-[clamp(30px,4vw,52px)] font-extrabold leading-none">Vamos conversar sobre o seu próximo aparelho?</h2>
            <div class="flex flex-wrap gap-3">
                <button type="button" class="btn btn-dark" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">Falar no WhatsApp</button>
                <a href="{{ route('contato') }}" class="btn btn-line">Ver contatos</a>
            </div>
        </div>
    </section>
</x-loja-layout>
