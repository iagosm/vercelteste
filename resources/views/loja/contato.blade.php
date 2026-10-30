<x-loja-layout titulo="Contato">
    <section class="wrap py-14 md:py-[72px]">
        <div class="grid items-start gap-10 lg:grid-cols-2 lg:gap-[72px]">
            <div>
                <p class="eyebrow text-moss">Contato</p>
                <h1 class="mt-4 font-display text-[clamp(40px,5.4vw,76px)] font-extrabold leading-[.98]">Fale com a gente.</h1>
                <p class="mt-5 max-w-[460px] text-lg leading-relaxed text-body">O jeito mais rápido é o WhatsApp. Se preferir, conte o que procura ao lado e a gente começa a conversa já com o contexto.</p>
                <button type="button" class="btn btn-dark mt-7 min-h-[60px] px-8 text-[17px]" @click="$store.whatsapp.abrir('Olá! Gostaria de falar com um vendedor sobre produtos Apple.')">
                    <x-icone nome="whatsapp" tamanho="22" /> Abrir WhatsApp
                </button>
                <dl class="mt-11 border-b border-line">
                    @foreach ([
                        'WhatsApp' => config('loja.whatsapp_exibicao'),
                        'Instagram' => config('loja.instagram'),
                        'E-mail' => config('loja.email'),
                        'Horário' => config('loja.horario'),
                        'Endereço' => config('loja.endereco'),
                    ] as $canal => $valor)
                        <div class="grid grid-cols-[130px_minmax(0,1fr)] gap-3 border-t border-line py-4">
                            <dt class="text-muted">{{ $canal }}</dt>
                            <dd>{{ $valor }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Só monta a mensagem do WhatsApp; nada é enviado ao servidor --}}
            <form class="card p-6 md:p-8"
                  x-data="{ nome: '', procura: @js($categorias->first()), mensagem: '' }"
                  @submit.prevent="$store.whatsapp.abrir('Olá! ' + (nome.trim() ? 'Me chamo ' + nome.trim() + '. ' : '') + 'Estou procurando: ' + procura + '.' + (mensagem.trim() ? ' ' + mensagem.trim() : ''))">
                <h2 class="font-display text-[28px] font-bold">Conte o que você procura</h2>
                <p class="mt-2 text-muted">Isso monta a mensagem do WhatsApp para você. Nada é guardado no site.</p>

                <div class="mt-[22px]">
                    <label for="contato-nome" class="lbl">Seu nome</label>
                    <input id="contato-nome" type="text" autocomplete="name" x-model="nome" placeholder="Como podemos te chamar?" class="inp">
                </div>
                <div class="mt-[18px]">
                    <label for="contato-procura" class="lbl">O que você procura?</label>
                    <select id="contato-procura" x-model="procura" class="inp">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                        <option value="Ainda não sei">Ainda não sei</option>
                    </select>
                </div>
                <div class="mt-[18px]">
                    <label for="contato-mensagem" class="lbl">Mensagem (opcional)</label>
                    <textarea id="contato-mensagem" rows="4" x-model="mensagem" placeholder="Para que você vai usar? Tem alguma faixa de preço em mente?" class="inp resize-y pt-3"></textarea>
                </div>
                <button type="submit" class="btn btn-dark mt-6 min-h-[56px] w-full">Enviar pelo WhatsApp</button>
            </form>
        </div>
    </section>

    <section class="border-t border-line bg-white">
        <div class="wrap grid gap-10 py-14 lg:grid-cols-[minmax(0,.8fr)_minmax(0,1.2fr)] lg:gap-[72px] lg:pb-24 lg:pt-20" x-data="{ aberta: 0 }">
            <h2 class="font-display text-[clamp(30px,3.8vw,48px)] font-bold leading-none">Perguntas frequentes</h2>
            <div class="border-b border-line">
                @foreach ($perguntas as $indice => $pergunta)
                    <div class="border-t border-line">
                        <h3>
                            <button type="button" class="flex min-h-16 w-full items-center justify-between gap-4 py-3 text-left text-lg font-semibold"
                                    @click="aberta = aberta === {{ $indice }} ? null : {{ $indice }}" :aria-expanded="aberta === {{ $indice }}">
                                <span>{{ $pergunta['pergunta'] }}</span>
                                <span class="font-display text-[28px] leading-none text-moss" x-text="aberta === {{ $indice }} ? '−' : '+'">+</span>
                            </button>
                        </h3>
                        <p x-show="aberta === {{ $indice }}" @if ($indice > 0) x-cloak @endif class="animate-rise pb-[22px] pr-10 leading-[1.65] text-body">{{ $pergunta['resposta'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-loja-layout>
