@php($endereco = $cliente['endereco'])

<x-loja-layout titulo="Meu perfil">
    <x-loja.conta titulo="Meu perfil" descricao="Com seus dados prontos, o vendedor não precisa perguntar tudo de novo a cada compra.">
        <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,.7fr)] lg:gap-8">
            <form method="POST" action="{{ route('cliente.perfil.update') }}" class="flex flex-col gap-5" novalidate>
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <p role="alert" class="rounded-card border border-rust bg-white p-4 text-sm font-semibold text-rust">{{ $errors->first() }}</p>
                @endif

                <section class="card p-5 md:p-7">
                    <h2 class="font-display text-[22px] font-bold md:text-[26px]">Dados pessoais</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="nome" class="lbl">Nome</label>
                            <input id="nome" name="nome" type="text" autocomplete="name" value="{{ old('nome', $cliente['nome']) }}" class="inp">
                        </div>
                        <div>
                            <label for="email" class="lbl">E-mail</label>
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email', $cliente['email']) }}" class="inp">
                        </div>
                        <div>
                            <label for="telefone" class="lbl">WhatsApp</label>
                            <input id="telefone" name="telefone" type="tel" inputmode="tel" autocomplete="tel" value="{{ old('telefone', $cliente['telefone']) }}" placeholder="(11) 99999-9999" class="inp">
                        </div>
                    </div>
                </section>

                <section class="card p-5 md:p-7">
                    <h2 class="font-display text-[22px] font-bold md:text-[26px]">Endereço de entrega</h2>
                    <p class="mt-1 text-sm text-muted">Opcional. Ajuda quando a entrega for por motoboy ou Correios.</p>
                    <div class="mt-4 grid grid-cols-6 gap-4">
                        <div class="col-span-6 md:col-span-2">
                            <label for="cep" class="lbl">CEP</label>
                            <input id="cep" name="endereco[cep]" type="text" inputmode="numeric" autocomplete="postal-code" value="{{ old('endereco.cep', $endereco['cep'] ?? '') }}" placeholder="00000-000" class="inp">
                        </div>
                        <div class="col-span-6 md:col-span-4">
                            <label for="rua" class="lbl">Rua</label>
                            <input id="rua" name="endereco[rua]" type="text" autocomplete="address-line1" value="{{ old('endereco.rua', $endereco['rua'] ?? '') }}" class="inp">
                        </div>
                        <div class="col-span-2">
                            <label for="numero" class="lbl">Número</label>
                            <input id="numero" name="endereco[numero]" type="text" inputmode="numeric" value="{{ old('endereco.numero', $endereco['numero'] ?? '') }}" class="inp">
                        </div>
                        <div class="col-span-4">
                            <label for="complemento" class="lbl">Complemento</label>
                            <input id="complemento" name="endereco[complemento]" type="text" autocomplete="address-line2" value="{{ old('endereco.complemento', $endereco['complemento'] ?? '') }}" placeholder="Apto, bloco…" class="inp">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label for="bairro" class="lbl">Bairro</label>
                            <input id="bairro" name="endereco[bairro]" type="text" value="{{ old('endereco.bairro', $endereco['bairro'] ?? '') }}" class="inp">
                        </div>
                        <div class="col-span-4 md:col-span-3">
                            <label for="cidade" class="lbl">Cidade</label>
                            <input id="cidade" name="endereco[cidade]" type="text" autocomplete="address-level2" value="{{ old('endereco.cidade', $endereco['cidade'] ?? '') }}" class="inp">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="estado" class="lbl">UF</label>
                            <select id="estado" name="endereco[estado]" autocomplete="address-level1" class="inp px-2.5">
                                <option value="">—</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado }}" @selected(old('endereco.estado', $endereco['estado'] ?? '') === $estado)>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn btn-dark w-full md:w-auto md:self-start">Salvar dados</button>
            </form>

            <section class="card p-5 md:p-7">
                <h2 class="font-display text-[22px] font-bold md:text-[26px]">Trocar senha</h2>
                <form method="POST" action="{{ route('cliente.senha.update') }}" class="mt-4 flex flex-col gap-4" novalidate x-data="{ mostrar: false }">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="senha_atual" class="lbl">Senha atual</label>
                        <input id="senha_atual" name="senha_atual" type="password" :type="mostrar ? 'text' : 'password'" autocomplete="current-password" class="inp">
                    </div>
                    <div>
                        <label for="password" class="lbl">Nova senha</label>
                        <input id="password" name="password" type="password" :type="mostrar ? 'text' : 'password'" autocomplete="new-password" class="inp">
                        <p class="mt-1 text-[13px] text-muted">Pelo menos 8 caracteres.</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="lbl">Repita a nova senha</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" :type="mostrar ? 'text' : 'password'" autocomplete="new-password" class="inp">
                    </div>
                    <label class="flex min-h-[44px] cursor-pointer items-center gap-2.5 text-sm">
                        <input type="checkbox" x-model="mostrar" class="size-5 rounded-[3px] border-[#8C968E] text-ink focus:ring-0">
                        Mostrar senhas
                    </label>
                    @if ($errors->senha->any())
                        <p role="alert" class="text-sm font-semibold text-rust">{{ $errors->senha->first() }}</p>
                    @endif
                    <button type="submit" class="btn btn-line w-full">Trocar senha</button>
                </form>
            </section>
        </div>
    </x-loja.conta>
</x-loja-layout>
