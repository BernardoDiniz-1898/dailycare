// Daily Care - JavaScript de Acessibilidade

// Navegacao por teclado - atalhos
document.addEventListener('keydown', function(e) {
    // Alt + 1: Ir para o conteudo principal
    if (e.altKey && e.key === '1') {
        e.preventDefault();
        const main = document.getElementById('conteudo-principal');
        if (main) main.focus();
    }

    // Alt + 2: Ir para a navegacao
    if (e.altKey && e.key === '2') {
        e.preventDefault();
        const nav = document.querySelector('nav[role="navigation"]');
        if (nav) nav.focus();
    }

    // Alt + 0: Ir para a barra de acessibilidade
    if (e.altKey && e.key === '0') {
        e.preventDefault();
        const toolbar = document.getElementById('acessibilidade-toolbar');
        if (toolbar) toolbar.focus();
    }
});

// Anunciar mudancas de status para leitores de tela
function announceToScreenReader(message, priority = 'polite') {
    const announcer = document.createElement('div');
    announcer.setAttribute('aria-live', priority);
    announcer.setAttribute('aria-atomic', 'true');
    announcer.className = 'sr-only';
    announcer.textContent = message;
    document.body.appendChild(announcer);
    setTimeout(() => announcer.remove(), 1000);
}

// Salvar e restaurar preferencias de acessibilidade
document.addEventListener('DOMContentLoaded', function() {
    // Restaurar alto contraste
    if (localStorage.getItem('altoContraste') === 'true') {
        document.body.classList.add('alto-contraste');
    }

    // Restaurar tamanho da fonte
    const savedFont = localStorage.getItem('fontScale');
    if (savedFont) {
        document.documentElement.style.fontSize = savedFont + '%';
    }
});
