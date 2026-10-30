<x-painel-guest-layout titulo="Entrar no painel">
    <div class="grid min-h-screen bg-paper lg:grid-cols-[minmax(0,1fr)_minmax(0,560px)]">
        <div class="flex flex-col justify-between gap-6 bg-ink px-5 py-7 text-snow lg:gap-10 lg:px-14 lg:py-12">
            <x-logo subtitulo="Painel" :tamanho="36" />
            <div>
                <p class="text-[13px] font-semibold uppercase tracking-[.14em] text-brand">Área da loja</p>
                <h1 class="mt-4 max-w-[560px] font-display text-[clamp(40px,5vw,72px)] font-extrabold leading-[.98]">Pedidos, clientes e produtos num só lugar.</h1>
                <p class="mt-5 max-w-[460px] text-[17px] leading-relaxed text-fog">Acompanhe cada conversa do WhatsApp até a entrega, sem perder ninguém no caminho.</p>
            </div>
            <p class="text-[13px] text-fog">Protótipo visual · dados fictícios</p>
        </div>

        <div class="flex items-center justify-center px-6 py-10">
            <div class="w-full max-w-[400px]">
                <p class="inline-flex items-center gap-2 rounded-full bg-ink px-3 py-1.5 text-xs font-bold uppercase tracking-[.12em] text-brand">Acesso restrito</p>
                <h2 class="mt-5 font-display text-4xl font-bold">Entrar no painel</h2>
                <p class="mt-1.5 text-muted">Acesso para quem atende e administra a loja.</p>

                <form method="POST" action="{{ route('painel.entrar.store') }}" class="mt-6 flex flex-col gap-4" novalidate x-data="{ mostrar: false }">
                    @csrf
                    <div>
                        <label for="email" class="lbl text-[13px] text-body">E-mail</label>
                        <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" placeholder="voce@loja.com" class="inp min-h-[44px]">
                    </div>
                    <div>
                        <label for="password" class="lbl text-[13px] text-body">Senha</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" :type="mostrar ? 'text' : 'password'" autocomplete="current-password" class="inp min-h-[44px] pr-24">
                            <button type="button" class="absolute right-1.5 top-0 min-h-[44px] px-3 text-sm font-semibold underline" @click="mostrar = ! mostrar" x-text="mostrar ? 'Ocultar' : 'Mostrar'">Mostrar</button>
                        </div>
                    </div>
                    @if ($errors->any())
                        <p role="alert" class="text-sm font-semibold text-rust">{{ $errors->first() }}</p>
                    @endif
                    <button type="submit" class="btn btn-dark w-full">Entrar</button>
                </form>

                <p class="mt-[22px] border-t border-line pt-3.5 text-[13px] leading-normal text-muted">
                    Protótipo: sem banco de dados, qualquer e-mail e senha entram. No sistema real, cada pessoa da equipe terá seu acesso e o administrador vai definir o que cada uma pode ver.
                </p>
            </div>
        </div>
    </div>
</x-painel-guest-layout>
