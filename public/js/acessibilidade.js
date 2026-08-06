/**
 * Daily Care - Acessibilidade Digital
 * WCAG 2.1 AA Compliant
 *
 * Funcionalidades:
 * - Redimensionamento de fontes (70% a 150%)
 * - Troca de tema (Claro / Escuro / Alto Contraste)
 * - Atalhos de teclado
 * - Persistencia de preferencias
 * - Anuncio para leitores de tela
 */

const DailyCare = {

    // =============================================
    // GERENCIAMENTO DE FONTE
    // =============================================
    fonte: {
        _escala: 100,
        _min: 70,
        _max: 150,
        _passo: 10,

        init() {
            const salvo = localStorage.getItem('dc_fonte');
            if (salvo) {
                this._escala = parseInt(salvo, 10);
                this._aplicar();
            }
        },

        maior() {
            if (this._escala < this._max) {
                this._escala += this._passo;
                this._aplicar();
                this._salvar();
                DailyCare.anunciar('Fonte aumentada para ' + this._escala + '%');
            }
        },

        menor() {
            if (this._escala > this._min) {
                this._escala -= this._passo;
                this._aplicar();
                this._salvar();
                DailyCare.anunciar('Fonte diminuida para ' + this._escala + '%');
            }
        },

        resetar() {
            this._escala = 100;
            this._aplicar();
            this._salvar();
            DailyCare.anunciar('Fonte resetada para 100%');
        },

        definir(valor) {
            valor = parseInt(valor, 10);
            if (valor < this._min) valor = this._min;
            if (valor > this._max) valor = this._max;
            this._escala = valor;
            this._aplicar();
            this._salvar();
        },

        atual() {
            return this._escala;
        },

        _aplicar() {
            document.documentElement.style.fontSize = this._escala + '%';
        },

        _salvar() {
            try {
                localStorage.setItem('dc_fonte', this._escala);
            } catch (e) { /* localStorage indisponivel */ }
        }
    },

    // =============================================
    // TEMA (Claro / Escuro / Alto Contraste)
    // =============================================
    tema: {
        _atual: 'claro',
        _opcoes: ['claro', 'escuro', 'alto-contraste'],
        _descricoes: {
            'claro': 'Tema claro',
            'escuro': 'Tema escuro',
            'alto-contraste': 'Alto contraste'
        },

        init() {
            const salvo = localStorage.getItem('dc_tema');
            if (salvo && this._opcoes.includes(salvo)) {
                this._atual = salvo;
            } else {
                // Respeitar preferencia do sistema
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    this._atual = 'escuro';
                }
            }
            this._aplicar();
            this._atualizarBotoes();
        },

        trocar(tema) {
            if (!this._opcoes.includes(tema) || tema === this._atual) return;

            this._atual = tema;
            this._aplicarComTransicao();
            this._salvar();
            this._atualizarBotoes();

            DailyCare.anunciar('Tema alterado para ' + this._descricoes[tema]);
        },

        proximo() {
            const idx = this._opcoes.indexOf(this._atual);
            const proximo = this._opcoes[(idx + 1) % this._opcoes.length];
            this.trocar(proximo);
        },

        _aplicarComTransicao() {
            const body = document.body;
            body.classList.add('tema-transition');
            this._aplicar();

            clearTimeout(this._timerTransicao);
            this._timerTransicao = setTimeout(() => {
                body.classList.remove('tema-transition');
            }, 400);
        },

        _aplicar() {
            const body = document.body;
            body.classList.remove('dark-mode', 'alto-contraste');

            if (this._atual === 'escuro') {
                body.classList.add('dark-mode');
            } else if (this._atual === 'alto-contraste') {
                body.classList.add('alto-contraste');
            }
        },

        _salvar() {
            try {
                localStorage.setItem('dc_tema', this._atual);
            } catch (e) { /* localStorage indisponivel */ }
        },

        _atualizarBotoes() {
            this._opcoes.forEach(t => {
                const btn = document.querySelector('[data-tema="' + t + '"]');
                if (btn) {
                    const isSelected = t === this._atual;
                    btn.setAttribute('aria-pressed', isSelected);
                    btn.classList.toggle('tema-ativo', isSelected);
                }
            });
        }
    },

    // =============================================
    // ATALHOS DE TECLADO
    // =============================================
    atalhos: {
        init() {
            document.addEventListener('keydown', (e) => this._handler(e));
        },

        _handler(e) {
            const tag = e.target.tagName.toLowerCase();
            if (tag === 'input' || tag === 'textarea' || tag === 'select') return;

            // Alt + 1: Pular para conteudo principal
            if (e.altKey && e.key === '1') {
                e.preventDefault();
                const main = document.getElementById('conteudo-principal');
                if (main) {
                    main.focus();
                    main.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            // Alt + 2: Pular para navegacao
            if (e.altKey && e.key === '2') {
                e.preventDefault();
                const nav = document.querySelector('nav[role="navigation"]');
                if (nav) {
                    const primeiroLink = nav.querySelector('a, button');
                    if (primeiroLink) primeiroLink.focus();
                }
            }

            // Alt + T: Proximo tema
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                DailyCare.tema.proximo();
            }

            // Alt + +: Aumentar fonte
            if (e.altKey && (e.key === '+' || e.key === '=')) {
                e.preventDefault();
                DailyCare.fonte.maior();
            }

            // Alt + -: Diminuir fonte
            if (e.altKey && e.key === '-') {
                e.preventDefault();
                DailyCare.fonte.menor();
            }

            // Alt + 0: Resetar fonte
            if (e.altKey && e.key === '0') {
                e.preventDefault();
                DailyCare.fonte.resetar();
            }

            // Escape: Fechar menus modais
            if (e.key === 'Escape') {
                const modalAberto = document.querySelector('.agenda-modal-backdrop.aberto, .auth-modal-backdrop.aberto');
                if (DailyCare.menu.estaAberto()) {
                    DailyCare.menu.fechar();
                } else if (!modalAberto) {
                    document.activeElement.blur();
                }
            }
        }
    },

    // =============================================
    // ANUNCIO PARA LEITORES DE TELA
    // =============================================
    anunciar(mensagem, prioridade = 'polite') {
        const el = document.createElement('div');
        el.setAttribute('aria-live', prioridade);
        el.setAttribute('aria-atomic', 'true');
        el.setAttribute('role', 'status');
        el.className = 'sr-only';
        el.textContent = mensagem;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    },

    // =============================================
    // MENU LATERAL (GAVETA)
    // =============================================
    menu: {
        _ultimoFoco: null,

        abrir() {
            const menu = document.getElementById('menu-lateral');
            const backdrop = document.getElementById('menu-backdrop');
            const botao = document.querySelector('.menu-hamburguer');
            if (!menu || !backdrop) return;

            this._ultimoFoco = document.activeElement;

            backdrop.hidden = false;
            requestAnimationFrame(() => backdrop.classList.add('aberto'));
            menu.classList.add('aberto');
            menu.setAttribute('aria-hidden', 'false');
            if (botao) botao.setAttribute('aria-expanded', 'true');

            document.body.style.overflow = 'hidden';

            const primeiroLink = menu.querySelector('a, button');
            if (primeiroLink) primeiroLink.focus();

            DailyCare.anunciar('Menu lateral aberto');
        },

        fechar() {
            const menu = document.getElementById('menu-lateral');
            const backdrop = document.getElementById('menu-backdrop');
            const botao = document.querySelector('.menu-hamburguer');
            if (!menu || !backdrop) return;

            menu.classList.remove('aberto');
            menu.setAttribute('aria-hidden', 'true');
            backdrop.classList.remove('aberto');
            setTimeout(() => { backdrop.hidden = true; }, 300);
            if (botao) botao.setAttribute('aria-expanded', 'false');

            document.body.style.overflow = '';

            if (this._ultimoFoco) {
                this._ultimoFoco.focus();
            } else if (botao) {
                botao.focus();
            }
        },

        estaAberto() {
            const menu = document.getElementById('menu-lateral');
            return menu && menu.classList.contains('aberto');
        }
    },

    // =============================================
    // AUTENTICACAO (fallback de navegacao)
    // Abre o modal quando ele existe; caso contrario,
    // redireciona para a pagina de login/registro.
    // =============================================
    auth: {
        abrir(aba) {
            const modal = window.DailyCare && window.DailyCare.authModal;
            if (modal) {
                modal.abrir(aba);
                return;
            }
            window.location.href = aba === 'registro' ? '/registro' : '/login';
        }
    },

    // =============================================
    // INICIALIZACAO
    // =============================================
    init() {
        this.fonte.init();
        this.tema.init();
        this.atalhos.init();
    }
};

// Expor no escopo global preservando modulos adicionados por outras paginas
// (ex.: window.DailyCare.authModal, .buscaClinicas, .perfilClinica)
window.DailyCare = Object.assign(window.DailyCare || {}, DailyCare);

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => DailyCare.init());
