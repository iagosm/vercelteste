import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Prévia da conversa antes de abrir o WhatsApp.
 * O número vem do atributo data-whatsapp do <body> (config/loja.php).
 */
Alpine.store('whatsapp', {
    aberto: false,
    mensagem: '',

    abrir(mensagem) {
        this.mensagem = mensagem;
        this.aberto = true;
    },

    fechar() {
        this.aberto = false;
    },

    get link() {
        const numero = (document.body.dataset.whatsapp || '').replace(/\D/g, '');

        return `https://wa.me/${numero}?text=${encodeURIComponent(this.mensagem)}`;
    },
});

const normalizar = (texto) => String(texto).normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

/**
 * Busca do cabeçalho da loja com sugestões de produtos.
 * Enviar o formulário leva para o catálogo com ?q=.
 */
Alpine.data('busca', (produtos, termoInicial = '') => ({
    termo: termoInicial,
    aberto: false,

    get resultados() {
        const termo = normalizar(this.termo.trim());

        if (!termo) {
            return [];
        }

        return produtos.filter((p) => normalizar(`${p.nome} ${p.categoria}`).includes(termo)).slice(0, 5);
    },
}));

/**
 * Conversa de exemplo da página inicial, imitando o WhatsApp:
 * cliente envia (1), vendedor digitando (2), resposta chega (3), e repete.
 */
Alpine.data('conversaDemo', () => ({
    etapa: 3,

    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        this.reproduzir();
    },

    reproduzir() {
        this.etapa = 0;

        [[500, 1], [1500, 2], [3800, 3], [10000, 0]].forEach(([atraso, etapa]) => {
            setTimeout(() => { this.etapa = etapa; }, atraso);
        });

        setTimeout(() => this.reproduzir(), 10800);
    },
}));

/**
 * Página do produto: escolha de cor, opção e vista da galeria.
 * O preço e as mensagens do WhatsApp acompanham a escolha.
 */
Alpine.data('produto', (produto) => ({
    cor: 0,
    opcao: 0,
    vista: 0,

    get preco() {
        return produto.preco + (produto.opcoes[this.opcao]?.adicional ?? 0);
    },

    get precoFormatado() {
        return `R$ ${this.preco.toLocaleString('pt-BR')}`;
    },

    get nomeCor() {
        return produto.cores[this.cor]?.nome ?? '';
    },

    get resumo() {
        const partes = [produto.nome + produto.sufixoCondicao];

        if (produto.opcoes.length > 1) {
            partes.push(produto.opcoes[this.opcao].rotulo);
        }

        if (produto.cores.length > 1) {
            partes.push(this.nomeCor);
        }

        return `${partes.join(' · ')} — ${this.precoFormatado}`;
    },

    get escolha() {
        const opcao = produto.opcoes.length > 1 ? ` ${produto.opcoes[this.opcao].rotulo}` : '';
        const cor = produto.cores.length > 1 ? `, na cor ${this.nomeCor}` : '';

        return `${produto.nome}${opcao}${produto.sufixoCondicao}${cor}`;
    },

    comprar() {
        this.$store.whatsapp.abrir(`Olá! Tenho interesse no ${this.escolha}. Gostaria de consultar a disponibilidade e as condições de pagamento.`);
    },

    consultarDisponibilidade() {
        this.$store.whatsapp.abrir(`Olá! O ${this.escolha} está disponível? Qual o prazo para receber?`);
    },

    tirarDuvidas() {
        this.$store.whatsapp.abrir(`Olá! Tenho algumas dúvidas sobre o ${produto.nome}${produto.sufixoCondicao}. Podem me ajudar?`);
    },
}));

Alpine.start();
