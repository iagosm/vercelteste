<?php

namespace App\Support;

class WhatsApp
{
    /**
     * Monta o link wa.me com a mensagem já preenchida.
     *
     * Sem telefone, usa o número da loja (config/loja.php).
     */
    public static function link(string $mensagem, ?string $telefone = null): string
    {
        $numero = preg_replace('/\D/', '', $telefone ?? config('loja.whatsapp'));

        return 'https://wa.me/'.$numero.'?text='.rawurlencode($mensagem);
    }

    /**
     * Mensagem padrão do botão "Comprar pelo WhatsApp".
     */
    public static function mensagemCompra(string $produto): string
    {
        return 'Olá! Tenho interesse no '.$produto.'. Gostaria de consultar a disponibilidade e as condições de pagamento.';
    }
}
