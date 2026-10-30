<?php

namespace App\Support;

use App\Enums\Condicao;
use App\Enums\Disponibilidade;
use App\Enums\StatusPedido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Dados fictícios usados pelas telas enquanto o banco não existe.
 *
 * Cada método devolve o mesmo formato que as views esperam, então a troca
 * pelo Eloquent pode ser feita método a método, sem mexer nas telas.
 *
 * @phpstan-type Cor array{nome: string, hex: string, familia: string}
 * @phpstan-type Opcao array{rotulo: string, adicional: int}
 * @phpstan-type Variacao array{cor: string, opcao: string, adicional: int, estoque: int}
 * @phpstan-type Categoria array{slug: string, nome: string, forma: string, cor: string, ativa: bool}
 * @phpstan-type Produto array{slug: string, categoria: string, modelo: string, forma: string, nome: string, linha: string, preco: int, condicao: Condicao, disponibilidade: Disponibilidade, selo: string, destaque: bool, cores: list<Cor>, rotulo_opcao: string, opcoes: list<Opcao>, especificacoes: array<string, string>, descricao: string, ativo: bool, variacoes: list<Variacao>, estoque: int}
 * @phpstan-type Anotacao array{data: string, texto: string}
 * @phpstan-type Cliente array{id: int, nome: string, telefone: string, email: string, desde: string, anotacoes: list<Anotacao>}
 * @phpstan-type Historico array{status: StatusPedido, em: Carbon, por: string}
 * @phpstan-type Pedido array{id: int, cliente_id: int, produto: string, valor: int, origem: string, status: StatusPedido, idade_horas: int, registrado_em: Carbon, historico: list<Historico>, observacao: string}
 * @phpstan-type PedidoCliente array{id: int, cliente_id: int, produto: string, valor: int, origem: string, status: StatusPedido, idade_horas: int, registrado_em: Carbon, historico: list<Historico>, observacao: string, pagamento: string, entrega: string}
 */
class DemoData
{
    public const ORIGENS = ['Site → WhatsApp', 'WhatsApp direto', 'Instagram'];

    /** @var Collection<int, Produto>|null */
    private static ?Collection $produtos = null;

    /** @var Collection<int, Pedido>|null */
    private static ?Collection $pedidos = null;

    /** @var Collection<int, PedidoCliente>|null */
    private static ?Collection $pedidosDaConta = null;

    /**
     * @return Collection<int, Categoria>
     */
    public static function categorias(): Collection
    {
        return collect([
            ['slug' => 'iphone', 'nome' => 'iPhone', 'forma' => 'phone', 'cor' => '#7C93C9', 'ativa' => true],
            ['slug' => 'macbook', 'nome' => 'MacBook', 'forma' => 'laptop', 'cor' => '#C9CCD0', 'ativa' => true],
            ['slug' => 'ipad', 'nome' => 'iPad', 'forma' => 'tablet', 'cor' => '#8FA7C4', 'ativa' => true],
            ['slug' => 'apple-watch', 'nome' => 'Apple Watch', 'forma' => 'watch', 'cor' => '#D8B4A6', 'ativa' => true],
            ['slug' => 'airpods', 'nome' => 'AirPods', 'forma' => 'airpods', 'cor' => '#F0F1EE', 'ativa' => true],
            ['slug' => 'acessorios', 'nome' => 'Acessórios', 'forma' => 'puck', 'cor' => '#ECEDEA', 'ativa' => true],
        ]);
    }

    /**
     * @return Categoria|null
     */
    public static function categoria(string $slug): ?array
    {
        return self::categorias()->firstWhere('slug', $slug);
    }

    /**
     * Todos os produtos, inclusive os inativos (o painel vê todos).
     *
     * @return Collection<int, Produto>
     */
    public static function produtos(): Collection
    {
        return self::$produtos ??= self::montarProdutos();
    }

    /**
     * Só o que aparece na loja.
     *
     * @return Collection<int, Produto>
     */
    public static function produtosAtivos(): Collection
    {
        return self::produtos()->where('ativo', true)->values();
    }

    /**
     * @return Produto|null
     */
    public static function produto(string $slug): ?array
    {
        return self::produtos()->firstWhere('slug', $slug);
    }

    /**
     * Destaques da página inicial, por aba.
     *
     * @return array<string, array{label: string, produtos: Collection<int, Produto>}>
     */
    public static function destaques(): array
    {
        $abas = [
            'procurados' => ['Mais procurados', ['iphone-17-pro', 'macbook-air-13-m3', 'apple-watch-series-10', 'airpods-pro-2']],
            'lancamentos' => ['Lançamentos', ['iphone-18-pro', 'macbook-pro-14-m4', 'iphone-17', 'ipad-pro-11-m4']],
            'seminovos' => ['Seminovos', ['iphone-14', 'iphone-13', 'macbook-air-13-m1', 'ipad-9-geracao']],
        ];

        return collect($abas)->map(fn (array $aba): array => [
            'label' => $aba[0],
            'produtos' => collect($aba[1])->map(fn (string $slug): ?array => self::produto($slug))->filter()->values(),
        ])->all();
    }

    /**
     * @return Collection<int, Cliente>
     */
    public static function clientes(): Collection
    {
        $cliente = fn (int $id, string $nome, string $desde, array $anotacoes): array => [
            'id' => $id,
            'nome' => $nome,
            'telefone' => '(00) 90000-000'.$id,
            'email' => str($nome)->before(' ')->lower()->ascii().'@exemplo.com',
            'desde' => $desde,
            'anotacoes' => collect($anotacoes)->map(fn (array $a): array => ['data' => $a[0], 'texto' => $a[1]])->all(),
        ];

        return collect([
            $cliente(1, 'Marina Costa', '12/08/2026', [['29/10', 'Pediu iPhone 17 Pro em azul pelo site.'], ['05/09', 'Comprou iPad; quer indicar uma amiga.']]),
            $cliente(2, 'Rafael Lima', '30/08/2026', [['28/10', 'Perguntou sobre AirPods Pro 2.']]),
            $cliente(3, 'Beatriz Souza', '02/09/2026', [['28/10', 'Comparando MacBook Air 8 GB e 16 GB.']]),
            $cliente(4, 'Henrique Alves', '15/09/2026', [['27/10', 'Prefere seminovo; veio pelo Instagram.']]),
            $cliente(5, 'Camila Duarte', '20/09/2026', [['26/10', 'Confirmou iPad Air; retira na loja.']]),
            $cliente(6, 'Lucas Ferreira', '01/10/2026', [['25/10', 'Apple Watch de presente.']]),
            $cliente(7, 'Patrícia Nunes', '04/10/2026', [['22/10', 'Compra concluída; pediu ajuda para configurar.']]),
            $cliente(8, 'Diego Martins', '09/10/2026', [['20/10', 'Desistiu do MacBook Pro; achou caro.']]),
        ]);
    }

    /**
     * @return Cliente|null
     */
    public static function cliente(int $id): ?array
    {
        return self::clientes()->firstWhere('id', $id);
    }

    /**
     * @return Collection<int, Pedido>
     */
    public static function pedidos(): Collection
    {
        return self::$pedidos ??= collect([
            self::pedidoFicticio(1042, 1, 'iPhone 17 Pro · 256 GB · Azul', 8490, 'Site → WhatsApp', StatusPedido::Novo, 3),
            self::pedidoFicticio(1041, 2, 'AirPods Pro 2', 1990, 'Site → WhatsApp', StatusPedido::Novo, 26),
            self::pedidoFicticio(1040, 3, 'MacBook Air 13" M3 · 16 GB · 512 GB · Meia-noite', 10490, 'Site → WhatsApp', StatusPedido::EmAtendimento, 20),
            self::pedidoFicticio(1039, 4, 'iPhone 14 · 128 GB · Roxo (seminovo)', 2790, 'Instagram', StatusPedido::AguardandoConfirmacao, 30),
            self::pedidoFicticio(1038, 5, 'iPad Air 11" · 256 GB · Azul', 5990, 'Site → WhatsApp', StatusPedido::Confirmado, 50),
            self::pedidoFicticio(1037, 6, 'Apple Watch Series 10 · 46 mm · Rosé', 4090, 'WhatsApp direto', StatusPedido::Confirmado, 60),
            self::pedidoFicticio(1036, 2, 'Capa de silicone + Carregador MagSafe', 739, 'Site → WhatsApp', StatusPedido::Concluido, 100),
            self::pedidoFicticio(1035, 7, 'iPhone 16 · 128 GB · Rosa', 5290, 'Site → WhatsApp', StatusPedido::Concluido, 120),
            self::pedidoFicticio(1034, 8, 'MacBook Pro 14" M4 · 16 GB · 512 GB', 12490, 'WhatsApp direto', StatusPedido::Cancelado, 140),
            self::pedidoFicticio(1033, 3, 'AirTag · 4 unidades', 999, 'Site → WhatsApp', StatusPedido::Concluido, 170),
            self::pedidoFicticio(1032, 1, 'iPad 10ª geração · 64 GB · Azul', 2990, 'Instagram', StatusPedido::Concluido, 200),
            self::pedidoFicticio(1031, 5, 'iPhone 15 · 256 GB · Verde', 4890, 'Site → WhatsApp', StatusPedido::Concluido, 240),
        ]);
    }

    /**
     * @return Pedido|null
     */
    public static function pedido(int $id): ?array
    {
        return self::pedidos()->firstWhere('id', $id);
    }

    /**
     * @return Collection<int, Pedido>
     */
    public static function pedidosDoCliente(int $clienteId): Collection
    {
        return self::pedidos()->where('cliente_id', $clienteId)->values();
    }

    /**
     * Pedidos do cliente logado em "Minha conta" (fictício), do mais recente ao mais antigo.
     *
     * @return Collection<int, PedidoCliente>
     */
    public static function pedidosDaMinhaConta(): Collection
    {
        return self::$pedidosDaConta ??= collect([
            [2006, 'iPhone 17 Pro · 256 GB · Azul', 8490, StatusPedido::Novo, 3, 'A combinar', 'A combinar'],
            [2005, 'AirPods Pro 2', 1990, StatusPedido::EmAtendimento, 26, 'Pix', 'Retirada na loja'],
            [2004, 'Apple Watch SE · 44 mm · Meia-noite', 2390, StatusPedido::AguardandoConfirmacao, 96, 'Cartão em 10x', 'Entrega por motoboy'],
            [2003, 'Carregador MagSafe · 2 m', 480, StatusPedido::Confirmado, 216, 'Pix', 'Envio pelos Correios'],
            [2002, 'iPad Air 11" · 128 GB · Azul', 5290, StatusPedido::Concluido, 360, 'Cartão em 6x', 'Retirada na loja'],
            [2001, 'Capa de silicone para iPhone · Preto', 349, StatusPedido::Cancelado, 528, 'A combinar', 'A combinar'],
            [1999, 'Cabo USB-C 2 m', 149, StatusPedido::Concluido, 962, 'Pix', 'Retirada na loja'],
            [1998, 'AirTag · 4 unidades', 999, StatusPedido::Concluido, 962, 'Pix', 'Retirada na loja'],
            [1990, 'MacBook Air 13" M1 · 8 GB · 256 GB (seminovo)', 4290, StatusPedido::Concluido, 1488, 'Cartão em 12x', 'Entrega por motoboy'],
        ])->map(fn (array $p): array => self::pedidoFicticio($p[0], 0, $p[1], $p[2], 'Site → WhatsApp', $p[3], $p[4]) + [
            'pagamento' => $p[5],
            'entrega' => $p[6],
        ]);
    }

    /**
     * @return PedidoCliente|null
     */
    public static function pedidoDaMinhaConta(int $id): ?array
    {
        return self::pedidosDaMinhaConta()->firstWhere('id', $id);
    }

    /**
     * @return list<array{pergunta: string, resposta: string}>
     */
    public static function perguntasFrequentes(): array
    {
        return [
            ['pergunta' => 'Como faço para comprar?', 'resposta' => 'Escolha o aparelho no catálogo e toque em Comprar pelo WhatsApp. A conversa abre com a mensagem pronta e um vendedor continua com você: confirma a disponibilidade, combina o pagamento e a entrega.'],
            ['pergunta' => 'O site tem carrinho ou pagamento online?', 'resposta' => 'Não nesta versão. O site é uma vitrine organizada: o pagamento é combinado direto com o vendedor, pelo WhatsApp.'],
            ['pergunta' => 'Posso tirar dúvidas antes de decidir?', 'resposta' => 'Pode. Use o botão Tirar dúvidas na página do produto ou fale com um vendedor. Sem compromisso e sem pressa.'],
            ['pergunta' => 'Quais são as formas de pagamento, prazos e garantia?', 'resposta' => '[A definir pela loja]. Esta resposta será preenchida com as condições reais de pagamento, entrega e garantia.'],
        ];
    }

    /**
     * Monta um pedido com o histórico de etapas até o status atual.
     *
     * @return Pedido
     */
    private static function pedidoFicticio(int $id, int $clienteId, string $produto, int $valor, string $origem, StatusPedido $status, int $idadeHoras): array
    {
        $etapas = match ($status) {
            StatusPedido::Cancelado => [StatusPedido::Novo, StatusPedido::EmAtendimento, StatusPedido::Cancelado],
            default => array_slice(StatusPedido::cases(), 0, array_search($status, StatusPedido::cases(), true) + 1),
        };

        $minutosTotais = $idadeHoras * 60;

        $historico = collect($etapas)->map(fn (StatusPedido $etapa, int $indice): array => [
            'status' => $etapa,
            'em' => now()->subMinutes((int) round($minutosTotais - ($indice * $minutosTotais / count($etapas)) * 0.9)),
            'por' => $indice === 0 && $origem === 'Site → WhatsApp' ? 'Registro do atendente' : 'Atendente demo',
        ])->all();

        return [
            'id' => $id,
            'cliente_id' => $clienteId,
            'produto' => $produto,
            'valor' => $valor,
            'origem' => $origem,
            'status' => $status,
            'idade_horas' => $idadeHoras,
            'registrado_em' => $historico[0]['em'],
            'historico' => $historico,
            'observacao' => '',
        ];
    }

    /**
     * @return Collection<int, Produto>
     */
    private static function montarProdutos(): Collection
    {
        $cor = fn (string $nome, string $hex, string $familia): array => ['nome' => $nome, 'hex' => $hex, 'familia' => $familia];
        $opcoes = fn (array $lista): array => collect($lista)->map(fn (array $o): array => ['rotulo' => $o[0], 'adicional' => $o[1]])->all();
        $garantia = '[A definir pela loja]';
        $branco = [$cor('Branco', '#F0F1EE', 'Branco')];
        $coresIphone = [$cor('Preto', '#2B2D30', 'Preto'), $cor('Branco', '#ECECEA', 'Branco'), $cor('Azul', '#7C93C9', 'Azul'), $cor('Rosa', '#E6B7C4', 'Rosa')];
        $armazenamento = $opcoes([['128 GB', 0], ['256 GB', 600], ['512 GB', 1800]]);

        $produtos = [
            ['iphone-18-pro', 'iphone', '18 Pro', 'phone', 'iPhone 18 Pro', 'A linha Pro mais recente', 9990, Condicao::Novo, Disponibilidade::EmEstoque, 'Lançamento', true,
                [$cor('Azul', '#7C93C9', 'Azul'), $cor('Preto', '#2B2D30', 'Preto'), $cor('Prata', '#DADCDD', 'Prata')], 'Armazenamento', $opcoes([['256 GB', 0], ['512 GB', 1800], ['1 TB', 3600]]),
                ['Linha' => 'iPhone 18 Pro', 'Conector' => 'USB-C', 'Garantia' => $garantia, 'Itens na caixa' => $garantia], 'Para quem quer o topo da linha. Confira cores e configurações disponíveis e fale com a gente.'],
            ['iphone-17-pro', 'iphone', '17 Pro', 'phone', 'iPhone 17 Pro', 'Linha Pro', 8490, Condicao::Novo, Disponibilidade::EmEstoque, 'Mais procurado', true,
                [$cor('Azul', '#7C93C9', 'Azul'), $cor('Laranja', '#D9824F', 'Laranja'), $cor('Prata', '#DADCDD', 'Prata')], 'Armazenamento', $opcoes([['256 GB', 0], ['512 GB', 1800], ['1 TB', 3600]]),
                ['Linha' => 'iPhone 17 Pro', 'Conector' => 'USB-C', 'Garantia' => $garantia, 'Itens na caixa' => $garantia], 'Uma escolha segura para quem quer desempenho e câmeras sem pagar pelo modelo mais novo.'],
            ['iphone-17', 'iphone', '17', 'phone', 'iPhone 17', 'Linha principal', 6490, Condicao::Novo, Disponibilidade::EmEstoque, '', true,
                $coresIphone, 'Armazenamento', $opcoes([['256 GB', 0], ['512 GB', 1700]]),
                ['Linha' => 'iPhone 17', 'Conector' => 'USB-C', 'Garantia' => $garantia, 'Itens na caixa' => $garantia], 'O equilíbrio da linha: bom para o dia a dia, em várias cores.'],
            ['iphone-16', 'iphone', '16', 'phone', 'iPhone 16', 'Linha anterior', 5290, Condicao::Novo, Disponibilidade::PoucasUnidades, '', false,
                [$cor('Preto', '#25272A', 'Preto'), $cor('Branco', '#ECECEA', 'Branco'), $cor('Rosa', '#E6B7C4', 'Rosa'), $cor('Verde-azulado', '#7FA9A6', 'Verde'), $cor('Ultramarino', '#5B6BD8', 'Azul')], 'Armazenamento', $armazenamento,
                ['Linha' => 'iPhone 16', 'Tela' => '6,1"', 'Conector' => 'USB-C', 'Garantia' => $garantia, 'Itens na caixa' => $garantia], 'Um modelo recente com preço mais confortável.'],
            ['iphone-15', 'iphone', '15', 'phone', 'iPhone 15', 'USB-C · Dynamic Island', 4290, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                [$cor('Preto', '#2B2B2D', 'Preto'), $cor('Azul', '#A9C3D6', 'Azul'), $cor('Verde', '#C5D5BE', 'Verde'), $cor('Amarelo', '#EFE3A8', 'Amarelo'), $cor('Rosa', '#EAC8CC', 'Rosa')], 'Armazenamento', $armazenamento,
                ['Linha' => 'iPhone 15', 'Tela' => '6,1"', 'Conector' => 'USB-C', 'Garantia' => $garantia], 'Um clássico equilibrado, com USB-C e boa câmera.'],
            ['iphone-14', 'iphone', '14', 'phone', 'iPhone 14', 'Seminovo revisado', 2790, Condicao::Seminovo, Disponibilidade::EmEstoque, '', false,
                [$cor('Preto', '#2B2D30', 'Preto'), $cor('Azul', '#A9C3D6', 'Azul'), $cor('Roxo', '#C9BEDD', 'Roxo')], 'Armazenamento', $opcoes([['128 GB', 0], ['256 GB', 500]]),
                ['Linha' => 'iPhone 14', 'Tela' => '6,1"', 'Conector' => 'Lightning', 'Condição' => 'Seminovo', 'Garantia' => $garantia], 'Preço acessível em um aparelho que ainda atende muito bem. A condição exata é confirmada no atendimento.'],
            ['iphone-13', 'iphone', '13', 'phone', 'iPhone 13', 'Seminovo revisado', 2290, Condicao::Seminovo, Disponibilidade::PoucasUnidades, '', false,
                [$cor('Preto', '#2B2D30', 'Preto'), $cor('Rosa', '#E6B7C4', 'Rosa'), $cor('Azul', '#A9C3D6', 'Azul')], 'Armazenamento', $opcoes([['128 GB', 0], ['256 GB', 450]]),
                ['Linha' => 'iPhone 13', 'Tela' => '6,1"', 'Conector' => 'Lightning', 'Condição' => 'Seminovo', 'Garantia' => $garantia], 'A porta de entrada para quem quer iPhone gastando menos.'],
            ['macbook-air-13-m3', 'macbook', 'MacBook Air', 'laptop', 'MacBook Air 13" M3', 'Fino, leve e silencioso', 7490, Condicao::Novo, Disponibilidade::EmEstoque, 'Mais procurado', true,
                [$cor('Meia-noite', '#2E3441', 'Preto'), $cor('Estelar', '#E2D9CB', 'Dourado'), $cor('Cinza-espacial', '#7D7E82', 'Cinza'), $cor('Prateado', '#D9DBDD', 'Prata')], 'Configuração', $opcoes([['8 GB · 256 GB', 0], ['16 GB · 512 GB', 3000]]),
                ['Tela' => '13,6" Liquid Retina', 'Chip' => 'Apple M3', 'Portas' => 'MagSafe · 2× Thunderbolt', 'Garantia' => $garantia], 'O notebook para quem quer leveza sem abrir mão de desempenho.'],
            ['macbook-pro-14-m4', 'macbook', 'MacBook Pro', 'laptop', 'MacBook Pro 14" M4', 'Tela Liquid Retina XDR', 12490, Condicao::Novo, Disponibilidade::EmEstoque, 'Lançamento', true,
                [$cor('Preto-espacial', '#2A2B2E', 'Preto'), $cor('Prateado', '#D8DADC', 'Prata')], 'Configuração', $opcoes([['16 GB · 512 GB', 0], ['24 GB · 1 TB', 4000]]),
                ['Tela' => '14,2" Liquid Retina XDR', 'Chip' => 'Apple M4', 'Portas' => '3× Thunderbolt · HDMI · SDXC', 'Garantia' => $garantia], 'Potência para edição, desenvolvimento e criação.'],
            ['macbook-air-13-m1', 'macbook', 'MacBook Air', 'laptop', 'MacBook Air 13" M1', 'Seminovo revisado', 4290, Condicao::Seminovo, Disponibilidade::EmEstoque, '', false,
                [$cor('Cinza-espacial', '#7D7E82', 'Cinza'), $cor('Prateado', '#D9DBDD', 'Prata')], 'Configuração', $opcoes([['8 GB · 256 GB', 0], ['8 GB · 512 GB', 700]]),
                ['Tela' => '13,3" Retina', 'Chip' => 'Apple M1', 'Condição' => 'Seminovo', 'Garantia' => $garantia], 'Ainda muito rápido para estudo e trabalho, por um preço bem menor.'],
            ['ipad-air-11', 'ipad', 'iPad Air', 'tablet', 'iPad Air 11"', 'Chip M2 · tela Liquid Retina', 5290, Condicao::Novo, Disponibilidade::EmEstoque, '', true,
                [$cor('Cinza-espacial', '#6E6F73', 'Cinza'), $cor('Estelar', '#E6DDD0', 'Dourado'), $cor('Azul', '#8FA7C4', 'Azul'), $cor('Roxo', '#B7A9CF', 'Roxo')], 'Armazenamento', $opcoes([['128 GB', 0], ['256 GB', 700], ['512 GB', 2200]]),
                ['Tela' => '11" Liquid Retina', 'Chip' => 'Apple M2', 'Acessórios' => 'Apple Pencil Pro', 'Garantia' => $garantia], 'Leve para carregar, forte para estudar, trabalhar e criar.'],
            ['ipad-pro-11-m4', 'ipad', 'iPad Pro', 'tablet', 'iPad Pro 11" M4', 'Ultra Retina XDR', 9490, Condicao::Novo, Disponibilidade::PoucasUnidades, '', false,
                [$cor('Preto-espacial', '#26272A', 'Preto'), $cor('Prateado', '#DADCDD', 'Prata')], 'Armazenamento', $opcoes([['256 GB', 0], ['512 GB', 1500], ['1 TB', 4500]]),
                ['Tela' => '11" Ultra Retina XDR', 'Chip' => 'Apple M4', 'Acessórios' => 'Apple Pencil Pro · Magic Keyboard', 'Garantia' => $garantia], 'O iPad mais avançado para ilustrar, editar vídeo e trabalhar em qualquer lugar.'],
            ['ipad-10-geracao', 'ipad', 'iPad', 'tablet', 'iPad 10ª geração', 'Tela de 10,9" · USB-C', 2990, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                [$cor('Azul', '#8EB1D8', 'Azul'), $cor('Rosa', '#E8B9C6', 'Rosa'), $cor('Amarelo', '#EFDD94', 'Amarelo'), $cor('Prateado', '#D8DADC', 'Prata')], 'Armazenamento', $opcoes([['64 GB', 0], ['256 GB', 1100]]),
                ['Tela' => '10,9" Liquid Retina', 'Conector' => 'USB-C', 'Garantia' => $garantia], 'A porta de entrada para o mundo iPad.'],
            ['ipad-9-geracao', 'ipad', 'iPad', 'tablet', 'iPad 9ª geração', 'Seminovo revisado', 1790, Condicao::Seminovo, Disponibilidade::EmEstoque, '', false,
                [$cor('Cinza-espacial', '#6E6F73', 'Cinza'), $cor('Prateado', '#D8DADC', 'Prata')], 'Armazenamento', $opcoes([['64 GB', 0], ['256 GB', 700]]),
                ['Tela' => '10,2" Retina', 'Conector' => 'Lightning', 'Condição' => 'Seminovo', 'Garantia' => $garantia], 'Boa opção para estudo e leitura, com ótimo custo-benefício.'],
            ['apple-watch-series-10', 'apple-watch', 'Series', 'watch', 'Apple Watch Series 10', 'Tela maior e mais fina', 3790, Condicao::Novo, Disponibilidade::EmEstoque, 'Mais procurado', true,
                [$cor('Preto-brilhante', '#1E1F22', 'Preto'), $cor('Rosé', '#D8B4A6', 'Rosa'), $cor('Prateado', '#CFD2D4', 'Prata')], 'Tamanho da caixa', $opcoes([['42 mm', 0], ['46 mm', 300]]),
                ['Tela' => 'OLED sempre ativa', 'Saúde' => 'ECG · frequência cardíaca', 'Resistência à água' => '50 m', 'Garantia' => $garantia], 'Acompanhe treinos, saúde e notificações em uma tela ampla.'],
            ['apple-watch-ultra-2', 'apple-watch', 'Ultra', 'watch', 'Apple Watch Ultra 2', 'Caixa de titânio · 49 mm', 7490, Condicao::Novo, Disponibilidade::PoucasUnidades, '', false,
                [$cor('Titânio Natural', '#B8B3A9', 'Dourado'), $cor('Titânio Preto', '#2C2D2F', 'Preto')], 'Tamanho da caixa', $opcoes([['49 mm', 0]]),
                ['Caixa' => 'Titânio de 49 mm', 'GPS' => 'Dupla frequência', 'Resistência à água' => '100 m', 'Garantia' => $garantia], 'Feito para aventura, com caixa robusta e bateria longa.'],
            ['apple-watch-se', 'apple-watch', 'SE', 'watch', 'Apple Watch SE', 'Essencial e acessível', 2190, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                [$cor('Meia-noite', '#2B3040', 'Preto'), $cor('Estelar', '#E3DACB', 'Dourado'), $cor('Prateado', '#D0D3D5', 'Prata')], 'Tamanho da caixa', $opcoes([['40 mm', 0], ['44 mm', 200]]),
                ['Tela' => 'OLED Retina', 'Saúde' => 'Frequência cardíaca', 'Garantia' => $garantia], 'O essencial de um smartwatch com preço mais leve.'],
            ['airpods-pro-2', 'airpods', 'AirPods Pro', 'airpods', 'AirPods Pro 2', 'Cancelamento ativo de ruído', 1990, Condicao::Novo, Disponibilidade::EmEstoque, 'Mais procurado', true,
                $branco, '', $opcoes([['Estojo USB-C', 0]]),
                ['Áudio' => 'Cancelamento ativo de ruído', 'Chip' => 'H2', 'Estojo' => 'MagSafe · USB-C', 'Garantia' => $garantia], 'Silêncio quando você quer, e o mundo ao redor quando precisa.'],
            ['airpods-4', 'airpods', 'AirPods', 'airpods', 'AirPods 4', 'Novo design · Áudio Espacial', 1290, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                $branco, '', $opcoes([['Estojo USB-C', 0]]),
                ['Áudio' => 'Áudio Espacial', 'Estojo' => 'USB-C', 'Garantia' => $garantia], 'Design leve e confortável, com som claro para música e chamadas.'],
            ['airpods-max', 'airpods', 'AirPods Max', 'headphones', 'AirPods Max', 'Over-ear com cancelamento de ruído', 4290, Condicao::Novo, Disponibilidade::SobEncomenda, '', false,
                [$cor('Meia-noite', '#2E3138', 'Preto'), $cor('Estelar', '#E1DDD3', 'Dourado'), $cor('Azul', '#9DB4CC', 'Azul'), $cor('Verde', '#B7CDBA', 'Verde')], '', $opcoes([['Padrão', 0]]),
                ['Formato' => 'Over-ear', 'Áudio' => 'Cancelamento ativo de ruído', 'Garantia' => $garantia], 'Som de alta fidelidade e construção premium.'],
            ['carregador-magsafe', 'acessorios', '', 'puck', 'Carregador MagSafe', 'Carregamento sem fio magnético', 390, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                $branco, 'Cabo', $opcoes([['1 m', 0], ['2 m', 90]]),
                ['Tipo' => 'Carregador sem fio magnético', 'Conector' => 'USB-C', 'Garantia' => $garantia], 'Encaixa com um clique e carrega sem plugar o cabo no aparelho.'],
            ['cabo-usb-c-2m', 'acessorios', '', 'cable', 'Cabo USB-C 2 m', 'Carga e dados', 149, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                $branco, '', $opcoes([['2 m', 0]]),
                ['Conectores' => 'USB-C para USB-C', 'Comprimento' => '2 metros'], 'Cabo resistente para carregar e sincronizar.'],
            ['airtag', 'acessorios', '', 'tag', 'AirTag', 'Encontre o que importa', 299, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                [$cor('Prateado', '#DADCDD', 'Prata')], 'Quantidade', $opcoes([['1 unidade', 0], ['4 unidades', 700]]),
                ['Função' => 'Localizador de objetos', 'Bateria' => 'Troca simples'], 'Prenda em chaves, mochila ou carteira e veja onde estão.'],
            ['capa-de-silicone-iphone', 'acessorios', '', 'phone', 'Capa de silicone para iPhone', 'Proteção com toque macio', 349, Condicao::Novo, Disponibilidade::EmEstoque, '', false,
                [$cor('Preto', '#1C1D1F', 'Preto'), $cor('Azul-meia-noite', '#2D3A55', 'Azul'), $cor('Rosa', '#E5B4C0', 'Rosa'), $cor('Verde-claro', '#C7DBB6', 'Verde')], '', $opcoes([['Padrão', 0]]),
                ['Material' => 'Silicone', 'Compatibilidade' => 'Confirme o modelo com o vendedor'], 'Proteção do dia a dia, com acabamento macio.'],
        ];

        $inativos = ['capa-de-silicone-iphone'];

        return collect($produtos)->map(function (array $p) use ($inativos): array {
            $produto = array_combine(
                ['slug', 'categoria', 'modelo', 'forma', 'nome', 'linha', 'preco', 'condicao', 'disponibilidade', 'selo', 'destaque', 'cores', 'rotulo_opcao', 'opcoes', 'especificacoes', 'descricao'],
                $p,
            );

            $variacoes = self::variacoesFicticias($produto);

            return $produto + [
                'ativo' => ! in_array($produto['slug'], $inativos, true),
                'variacoes' => $variacoes,
                'estoque' => array_sum(array_column($variacoes, 'estoque')),
            ];
        });
    }

    /**
     * Variações com estoque do painel. Os produtos sem dados próprios recebem
     * a primeira cor combinada com cada opção.
     *
     * @param  array<string, mixed>  $produto
     * @return list<Variacao>
     */
    private static function variacoesFicticias(array $produto): array
    {
        $variacao = fn (string $cor, string $opcao, int $adicional, int $estoque): array => compact('cor', 'opcao', 'adicional', 'estoque');

        $conhecidas = [
            'iphone-18-pro' => [$variacao('Azul', '256 GB', 0, 2), $variacao('Preto', '256 GB', 0, 1), $variacao('Azul', '512 GB', 1800, 1)],
            'iphone-17-pro' => [$variacao('Azul', '256 GB', 0, 3), $variacao('Laranja', '256 GB', 0, 2), $variacao('Prata', '512 GB', 1800, 1)],
            'iphone-17' => [$variacao('Preto', '256 GB', 0, 4), $variacao('Branco', '256 GB', 0, 3), $variacao('Rosa', '512 GB', 1700, 2)],
            'iphone-16' => [$variacao('Rosa', '128 GB', 0, 1), $variacao('Preto', '128 GB', 0, 0)],
            'iphone-14' => [$variacao('Roxo', '128 GB', 0, 2), $variacao('Preto', '128 GB', 0, 1)],
            'macbook-air-13-m3' => [$variacao('Meia-noite', '8 GB · 256 GB', 0, 3), $variacao('Meia-noite', '16 GB · 512 GB', 3000, 2)],
            'macbook-pro-14-m4' => [$variacao('Preto-espacial', '16 GB · 512 GB', 0, 1), $variacao('Prateado', '16 GB · 512 GB', 0, 1)],
            'ipad-air-11' => [$variacao('Azul', '128 GB', 0, 3), $variacao('Azul', '256 GB', 700, 2)],
            'apple-watch-series-10' => [$variacao('Rosé', '42 mm', 0, 0), $variacao('Preto-brilhante', '46 mm', 300, 0)],
            'airpods-pro-2' => [$variacao('Branco', 'Estojo USB-C', 0, 12)],
            'carregador-magsafe' => [$variacao('Branco', '1 m', 0, 9), $variacao('Branco', '2 m', 90, 6)],
            'capa-de-silicone-iphone' => [$variacao('Preto', 'Padrão', 0, 1)],
        ];

        return $conhecidas[$produto['slug']] ?? collect($produto['opcoes'])
            ->map(fn (array $opcao): array => $variacao($produto['cores'][0]['nome'], $opcao['rotulo'], $opcao['adicional'], 2))
            ->all();
    }
}
