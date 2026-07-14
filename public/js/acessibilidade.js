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
            this._aplicar();
            this._salvar();
            this._atualizarBotoes();

            DailyCare.anunciar('Tema alterado para ' + this._descricoes[tema]);
        },

        proximo() {
            const idx = this._opcoes.indexOf(this._atual);
            const proximo = this._opcoes[(idx + 1) % this._opcoes.length];
            this.trocar(proximo);
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
                document.activeElement.blur();
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
    // INICIALIZACAO
    // =============================================
    init() {
        this.fonte.init();
        this.tema.init();
        this.atalhos.init();
    }
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => DailyCare.init());
