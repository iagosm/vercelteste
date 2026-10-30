<?php

namespace App\Enums;

enum StatusPedido: string
{
    case Novo = 'novo';
    case EmAtendimento = 'atendimento';
    case AguardandoConfirmacao = 'aguardando';
    case Confirmado = 'confirmado';
    case Concluido = 'concluido';
    case Cancelado = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::Novo => 'Novo',
            self::EmAtendimento => 'Em atendimento',
            self::AguardandoConfirmacao => 'Aguardando confirmação',
            self::Confirmado => 'Confirmado',
            self::Concluido => 'Concluído',
            self::Cancelado => 'Cancelado',
        };
    }

    public function descricao(): string
    {
        return match ($this) {
            self::Novo => 'Cliente chamou; ninguém respondeu ainda',
            self::EmAtendimento => 'Vendedor conversando com o cliente',
            self::AguardandoConfirmacao => 'Cliente decidindo ou pagamento pendente',
            self::Confirmado => 'Venda fechada; falta entregar',
            self::Concluido => 'Entregue e finalizado',
            self::Cancelado => 'Venda não realizada',
        };
    }

    /**
     * Explicação do status escrita para o cliente, na área "Minha conta".
     */
    public function descricaoCliente(): string
    {
        return match ($this) {
            self::Novo => 'Recebemos sua mensagem. Um vendedor vai responder em breve pelo WhatsApp.',
            self::EmAtendimento => 'Um vendedor está conversando com você pelo WhatsApp.',
            self::AguardandoConfirmacao => 'Esperando sua confirmação ou o pagamento.',
            self::Confirmado => 'Compra confirmada. Agora falta a entrega.',
            self::Concluido => 'Pedido entregue. Se precisar de ajuda, é só chamar.',
            self::Cancelado => 'Este pedido foi cancelado. Se quiser retomar, fale com a gente.',
        };
    }

    public function proximoPasso(): string
    {
        return match ($this) {
            self::Novo => 'Responder o cliente e passar para Em atendimento',
            self::EmAtendimento => 'Combinar valores e aguardar a decisão',
            self::AguardandoConfirmacao => 'Confirmar o pagamento e a entrega',
            self::Confirmado => 'Entregar e marcar como Concluído',
            self::Concluido, self::Cancelado => 'Nada pendente',
        };
    }

    /**
     * Próxima etapa do fluxo; pedidos concluídos ou cancelados não avançam.
     */
    public function proximo(): ?self
    {
        return match ($this) {
            self::Novo => self::EmAtendimento,
            self::EmAtendimento => self::AguardandoConfirmacao,
            self::AguardandoConfirmacao => self::Confirmado,
            self::Confirmado => self::Concluido,
            self::Concluido, self::Cancelado => null,
        };
    }

    public function emAberto(): bool
    {
        return in_array($this, [self::Novo, self::EmAtendimento, self::AguardandoConfirmacao], true);
    }

    public function corFundo(): string
    {
        return match ($this) {
            self::Novo => '#E6FFC4',
            self::EmAtendimento => '#DCE6F7',
            self::AguardandoConfirmacao => '#FFE9B0',
            self::Confirmado => '#CFEBD6',
            self::Concluido => '#0B110D',
            self::Cancelado => '#E6E8E2',
        };
    }

    public function corTexto(): string
    {
        return match ($this) {
            self::Novo => '#1F3A0F',
            self::EmAtendimento => '#1D2F55',
            self::AguardandoConfirmacao => '#5C4300',
            self::Confirmado => '#12482B',
            self::Concluido => '#B8FF65',
            self::Cancelado => '#4B544D',
        };
    }

    public function corBarra(): string
    {
        return match ($this) {
            self::Novo => '#B8FF65',
            self::EmAtendimento => '#7C93C9',
            self::AguardandoConfirmacao => '#E8B93A',
            self::Confirmado => '#3E9B4F',
            self::Concluido => '#0B110D',
            self::Cancelado => '#A6ADA7',
        };
    }
}
