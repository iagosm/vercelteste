# Gustavo · Especialista Apple

> Catálogo online de produtos Apple originais, com compra fechada por uma pessoa, no WhatsApp.

O site mostra os produtos, ajuda o cliente a escolher e, na hora de comprar, abre o WhatsApp com a mensagem já pronta. Não tem checkout nem pagamento no site. Disponibilidade, forma de pagamento e entrega são combinadas direto com o vendedor.

Por trás da loja tem um **painel administrativo** onde a equipe registra os pedidos, acompanha cada venda até a entrega e cuida do catálogo, dos clientes e das configurações da loja.

---

## Como funciona uma compra

```
  Site                 WhatsApp                Painel                  Status
┌──────────────┐     ┌──────────────────┐     ┌──────────────────┐    ┌──────────────────┐
│ Cliente      │ ──▶ │ Conversa abre    │ ──▶ │ Vendedor         │ ─▶ │ Novo → ... →     │
│ escolhe o    │     │ com a mensagem   │     │ registra o       │    │ Concluído        │
│ produto e    │     │ pronta: produto, │     │ pedido, ligado   │    │ (com histórico)  │
│ clica em     │     │ cor, config.,    │     │ ao cliente e ao  │    │                  │
│ comprar      │     │ condição e       │     │ produto          │    │                  │
│              │     │ pagamento        │     │                  │    │                  │
└──────────────┘     └──────────────────┘     └──────────────────┘    └──────────────────┘
   automático             manual              manual (automatizável)     manual
```

Clicar em comprar **não cria um pedido sozinho**. O pedido nasce no painel, quando o vendedor registra a venda que está conversando no WhatsApp. Assim o fluxo funciona desde o primeiro dia, sem integração com a API do WhatsApp.

Exemplo da mensagem gerada pelo site:

> Olá! Tenho interesse no iPhone 17 Pro 256 GB, na cor Azul. Gostaria de consultar a disponibilidade e as condições de pagamento.

### Status do pedido

| Status | Significa |
| --- | --- |
| **Novo** | Cliente chamou; ninguém respondeu ainda |
| **Em atendimento** | Vendedor conversando com o cliente |
| **Aguardando confirmação** | Cliente decidindo ou pagamento pendente |
| **Confirmado** | Venda fechada; falta entregar |
| **Concluído** | Entregue e finalizado |
| **Cancelado** | Venda não realizada |

---

## As duas áreas do sistema

### 🛍️ Loja (o que o cliente vê)

| Tela | Rota | O que tem |
| --- | --- | --- |
| Início | `/` | Destaques, categorias com "a partir de R$", como funciona, quem somos |
| Catálogo | `/catalogo` | Busca, filtros por categoria, preço, modelo, armazenamento, cor, condição e disponibilidade, ordenação |
| Produto | `/produto/{slug}` | Cores, variações com preço adicional, especificações, botão **Comprar pelo WhatsApp** |
| Sobre nós | `/sobre` | História da loja, como a equipe trabalha, quem atende |
| Contato | `/contato` | Canais, horário, endereço, formulário que monta a mensagem do WhatsApp, perguntas frequentes |
| Entrar / Criar conta | `/entrar` | Login e cadastro do cliente |
| Minha conta | `/conta` | Pedidos que o vendedor registrou para o cliente e os dados dele |

**Novo e seminovo** são atributos centrais: aparecem no card, nos filtros, na página do produto e na mensagem do WhatsApp.

### 🔐 Painel administrativo (a equipe da loja)

| Tela | Rota | O que tem |
| --- | --- | --- |
| Login da equipe | `/painel/entrar` | Acesso restrito a quem atende e administra a loja |
| Visão geral | `/painel` | Indicadores clicáveis, pedidos que precisam de atenção, quadro por status, valores, origem dos pedidos, estoque baixo |
| Pedidos | `/painel/pedidos` | Lista com busca e filtro por status, registrar pedido |
| Pedido | `/painel/pedidos/{id}` | Andamento, mudar status, abrir conversa no WhatsApp, observações internas, histórico |
| Produtos | `/painel/produtos` | Lista com busca e filtro por categoria, estoque, ativo/inativo |
| Produto (criar/editar) | `/painel/produtos/{id}/editar` | Dados básicos, condição, preço, disponibilidade, variações com estoque, imagens, visibilidade |
| Clientes | `/painel/clientes` | Lista com telefone, data de cadastro e pedidos |
| Cliente | `/painel/clientes/{id}` | Pedidos associados e histórico de atendimento |
| Categorias | `/painel/categorias` | Criar, ativar e remover categorias |
| Configurações | `/painel/configuracoes` | Nome da loja, número do WhatsApp usado nos botões do site, horário de atendimento |

As duas áreas são **responsivas**: todas as telas foram pensadas para celular e desktop.

---

## Stack

- **PHP 8.4** + **Laravel 13**
- **Blade** para as telas, sem React nem Vue
- **Tailwind CSS** + **Alpine.js** (vindos do Laravel Breeze) para estilo e interações simples
- **Vite** para o build do front-end
- **SQLite** em desenvolvimento
- **Pest** para os testes
- **Laravel Pint** para padronização do código

---

## Rodando o projeto

Pré-requisitos: PHP 8.4, Composer e Node.js.

```bash
# instala dependências, cria o .env, gera a chave, roda as migrations e faz o build
composer run setup

# sobe o servidor, a fila, os logs e o Vite juntos
composer run dev
```

Testes e formatação:

```bash
php artisan test
vendor/bin/pint
```

---

## Andamento

- [x] Protótipo visual de todas as telas (loja e painel, desktop e celular)
- [ ] Telas em Blade com dados de exemplo
- [ ] Rotas e controllers da loja e do painel
- [ ] Banco de dados: produtos, variações, categorias, clientes, pedidos e configurações
- [ ] Login de clientes e login da equipe
- [ ] Botões do WhatsApp usando o número cadastrado nas configurações

### Fica para o futuro

- Criar rascunho de pedido automaticamente a partir da conversa (WhatsApp Business API)
- Avisos ao cliente quando o status do pedido mudar

**Fora do escopo:** pagamento online. A venda é sempre fechada na conversa.

---

<sub>Apple, iPhone, MacBook, iPad, Apple Watch e AirPods são marcas da Apple Inc. Este projeto não tem vínculo oficial com a Apple.</sub>
