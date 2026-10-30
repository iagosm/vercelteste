<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dados da loja
    |--------------------------------------------------------------------------
    |
    | Valores provisórios até a tela de Configurações do painel salvar no
    | banco. O número do WhatsApp é usado em todos os botões de compra do
    | site e deve ter só dígitos, com DDI e DDD (ex.: 5511999999999).
    |
    */

    'nome' => env('LOJA_NOME', 'Gustavo'),

    'whatsapp' => env('LOJA_WHATSAPP', '00000000000'),

    'whatsapp_exibicao' => env('LOJA_WHATSAPP_EXIBICAO', '[NÚMERO DA LOJA]'),

    'horario' => env('LOJA_HORARIO', '[DIAS E HORÁRIOS DE ATENDIMENTO]'),

    'instagram' => env('LOJA_INSTAGRAM', '[@PERFIL]'),

    'email' => env('LOJA_EMAIL', '[CONTATO@EXEMPLO]'),

    'endereco' => env('LOJA_ENDERECO', '[ENDEREÇO, SE HOUVER LOJA FÍSICA]'),

    'cnpj' => env('LOJA_CNPJ', '[00.000.000/0000-00]'),

];
