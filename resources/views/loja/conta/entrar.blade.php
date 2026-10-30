<x-loja-layout :titulo="$cadastro ? 'Criar conta' : 'Entrar'">
    <section class="wrap py-14 md:pb-[88px] md:pt-[72px]">
        <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,480px)] lg:gap-20">
            <div>
                <p class="eyebrow text-moss">Minha conta</p>
                <h1 class="mt-4 font-display text-[clamp(40px,5.4vw,76px)] font-extrabold leading-[.98]">{{ $cadastro ? 'Criar sua conta.' : 'Entre na sua conta.' }}</h1>
                <p class="mt-5 max-w-[460px] text-lg leading-relaxed text-body">
                    Com uma conta você acompanha seus pedidos e guarda seus dados, para não repetir tudo a cada conversa. A compra continua sendo combinada pelo WhatsApp.
                </p>
                <ul class="mt-7 max-w-[460px] border-b border-line">
                    <li class="grid grid-cols-[28px_minmax(0,1fr)] gap-3 border-t border-line py-3.5"><span class="font-display font-bold text-moss">01</span><span>Veja o andamento dos pedidos que o vendedor registrou para você.</span></li>
                    <li class="grid grid-cols-[28px_minmax(0,1fr)] gap-3 border-t border-line py-3.5"><span class="font-display font-bold text-moss">02</span><span>Seus dados ficam prontos para o próximo atendimento.</span></li>
                </ul>
            </div>

            <div class="card p-7">
                <form method="POST" action="{{ $cadastro ? route('cliente.cadastro.store') : route('cliente.entrar.store') }}" class="flex flex-col gap-4" novalidate x-data="{ mostrar: false }">
                    @csrf

                    @if ($cadastro)
                        <div>
                            <label for="name" class="lbl">Nome</label>
                            <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" placeholder="Como devemos te chamar" class="inp">
                        </div>
                    @endif

                    <div>
                        <label for="email" class="lbl">E-mail</label>
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="voce@exemplo.com" class="inp">
                    </div>

                    <div>
                        <label for="password" class="lbl">Senha</label>
                        <div class="relative">
                            <input id="password" name="password" :type="mostrar ? 'text' : 'password'" type="password" autocomplete="{{ $cadastro ? 'new-password' : 'current-password' }}" placeholder="Sua senha" class="inp pr-24">
                            <button type="button" class="absolute right-1.5 top-0.5 min-h-[44px] px-3 text-sm font-semibold underline" @click="mostrar = ! mostrar" x-text="mostrar ? 'Ocultar' : 'Mostrar'">Mostrar</button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <p role="alert" class="text-sm font-semibold text-rust">{{ $errors->first() }}</p>
                    @endif

                    <button type="submit" class="btn btn-dark min-h-[56px] w-full">{{ $cadastro ? 'Criar conta' : 'Entrar' }}</button>
                </form>

                <p class="mt-[18px] text-center text-[14.5px] text-muted">
                    {{ $cadastro ? 'Já tem conta?' : 'Ainda não tem conta?' }}
                    <a href="{{ $cadastro ? route('cliente.entrar') : route('cliente.cadastro') }}" class="inline-flex min-h-[44px] items-center font-semibold text-ink underline">{{ $cadastro ? 'Entrar' : 'Criar conta' }}</a>
                </p>
                <p class="mt-1.5 border-t border-line-soft pt-3.5 text-[13px] leading-normal text-muted">
                    Protótipo: sem banco de dados, qualquer e-mail e senha entram e nada é salvo. A recuperação de senha entra quando houver sistema real.
                </p>
            </div>
        </div>
    </section>
</x-loja-layout>
