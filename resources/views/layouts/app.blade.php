<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daily Care - Marketplace acessivel de fisioterapia conectando clinicas a pacientes com deficiencias motoras.">
    <meta name="theme-color" content="#1A56DB">
    <title>@yield('titulo', 'Daily Care') - Marketplace de Fisioterapia</title>

    {{-- Google Fonts: Roboto --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body>
    {{-- =============================================
         SKIP LINK - Pular para conteudo principal
         Acessibilidade: WCAG 2.4.1 (Bypass Blocks)
         ============================================= --}}
    <a href="#conteudo-principal" class="skip-link">
        Pular para o conteudo principal
    </a>

    {{-- =============================================
         BARRA DE ACESSIBILIDADE
         Ajustes de interface: fonte, contraste, Libras
         ============================================= --}}
    <div id="acessibilidade-toolbar" class="acessibilidade-bar" role="toolbar" aria-label="Ferramentas de acessibilidade">
        <span class="sr-only" id="acessibilidade-info">Use Alt+1 para pular ao conteudo, Alt+2 para a navegacao</span>

        <button type="button"
                onclick="DailyCare.fonte.maior()"
                aria-label="Aumentar tamanho da fonte"
                title="Aumentar fonte (Alt + +)">
            <span aria-hidden="true">A</span><span aria-hidden="true" style="font-size:0.7em">+</span>
        </button>

        <button type="button"
                onclick="DailyCare.fonte.menor()"
                aria-label="Diminuir tamanho da fonte"
                title="Diminuir fonte (Alt + -)">
            <span aria-hidden="true">A</span><span aria-hidden="true" style="font-size:0.7em">-</span>
        </button>

        <button type="button"
                onclick="DailyCare.fonte.resetar()"
                aria-label="Resetar tamanho da fonte para o padrao"
                title="Resetar fonte">
            <span aria-hidden="true">100%</span>
        </button>

        <div class="tema-seletor" role="radiogroup" aria-label="Selecionar tema">
            <button type="button"
                    data-tema="claro"
                    onclick="DailyCare.tema.trocar('claro')"
                    role="radio"
                    aria-checked="true"
                    aria-pressed="true"
                    aria-label="Tema claro"
                    title="Tema claro">
                <span aria-hidden="true">&#x2600;</span> Claro
            </button>
            <button type="button"
                    data-tema="escuro"
                    onclick="DailyCare.tema.trocar('escuro')"
                    role="radio"
                    aria-checked="false"
                    aria-pressed="false"
                    aria-label="Tema escuro"
                    title="Tema escuro (Alt + T)">
                <span aria-hidden="true">&#x1F319;</span> Escuro
            </button>
            <button type="button"
                    data-tema="alto-contraste"
                    onclick="DailyCare.tema.trocar('alto-contraste')"
                    role="radio"
                    aria-checked="false"
                    aria-pressed="false"
                    aria-label="Alto contraste"
                    title="Alto contraste">
                <span aria-hidden="true">&#x25CF;</span> Alto Contraste
            </button>
        </div>

        <div id="vlibras-widget" aria-label="Widget de traducao para Libras"></div>
    </div>

    {{-- =============================================
         CABECALHO / NAVEGACAO PRINCIPAL
         Acessibilidade: landmark nav, aria-label
         ============================================= --}}
    <header class="site-header" role="banner">
        <nav role="navigation" aria-label="Navegacao principal">
            <div style="display:flex; align-items:center; gap:32px;">
                <a href="{{ route('home') }}" class="site-logo" aria-label="Daily Care - Pagina inicial">
                    <span aria-hidden="true">&#x2695;</span> Daily Care
                </a>

                <ul class="nav-links" role="menubar">
                    <li role="none">
                        <a href="{{ route('clinicas.index') }}" class="nav-link" role="menuitem">
                            <span aria-hidden="true">&#x1F50D;</span> Clinicas
                        </a>
                    </li>
                </ul>
            </div>

            <ul class="nav-links" role="menubar">
                @auth
                    <li role="none">
                        <a href="{{ route('dashboard') }}" class="nav-link" role="menuitem">
                            <span aria-hidden="true">&#x1F4CA;</span> Dashboard
                        </a>
                    </li>
                    <li role="none" style="display:flex; align-items:center; gap:8px;">
                        <span aria-hidden="true">&#x1F464;</span>
                        <span style="font-weight:600;">{{ Auth::user()->nome }}</span>
                    </li>
                    <li role="none">
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="nav-link" role="menuitem" style="background:none; border:none; cursor:pointer;">
                                <span aria-hidden="true">&#x1F6AA;</span> Sair
                            </button>
                        </form>
                    </li>
                @else
                    <li role="none">
                        <a href="{{ route('login') }}" class="nav-link" role="menuitem">
                            <span aria-hidden="true">&#x1F511;</span> Entrar
                        </a>
                    </li>
                    <li role="none">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm" role="menuitem">
                            <span aria-hidden="true">&#x2795;</span> Cadastrar
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </header>

    {{-- =============================================
         MENSAGENS DE FEEDBACK (Flash)
         aria-live para leitores de tela
         ============================================= --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-6 mt-6" role="status" aria-live="polite" aria-atomic="true">
            <div class="alert alert-success">
                <span aria-hidden="true">&#x2705;</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-6 mt-6" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="alert alert-error">
                <span aria-hidden="true">&#x274C;</span>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-7xl mx-auto px-6 mt-6" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="alert alert-error">
                <div>
                    <strong>Erros encontrados:</strong>
                    <ul style="margin-top:4px; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- =============================================
         CONTEUDO PRINCIPAL
         ============================================= --}}
    <main id="conteudo-principal" class="max-w-7xl mx-auto px-6 py-10 w-full flex-1" role="main" tabindex="-1" aria-label="Conteudo principal">
        @yield('conteudo')
    </main>

    {{-- =============================================
         RODAPE
         ============================================= --}}
    <footer role="contentinfo" class="site-footer">
        <div class="max-w-7xl mx-auto">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:40px;">
                <div>
                    <h2>
                        <span aria-hidden="true">&#x2695;</span> Daily Care
                    </h2>
                    <p>
                        Marketplace acessivel de fisioterapia que conecta clinicas independentes a pacientes com deficiencias motoras.
                    </p>
                </div>

                <div>
                    <h2>Links</h2>
                    <nav aria-label="Links do rodape">
                        <ul class="site-footer-links">
                            <li><a href="{{ route('clinicas.index') }}">Buscar Clinicas</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}">Meu Painel</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Entrar</a></li>
                                <li><a href="{{ route('register') }}">Cadastrar</a></li>
                            @endauth
                        </ul>
                    </nav>
                </div>

                <div>
                    <h2>Acessibilidade</h2>
                    <p>
                        Este site atende as diretrizes <strong>WCAG 2.1 Nivel AA</strong>. Navegacao completa via teclado, leitores de tela e modo alto contraste.
                    </p>
                    <p style="margin-top:8px;">
                        Integracao com <strong>V-Libras</strong> para traducao em Libras.
                    </p>
                    <p style="margin-top:12px; font-size:0.8125rem; color:#9CA3AF;">
                        Atalhos: Alt+1 (conteudo), Alt+2 (nav), Alt+T (tema), Alt+/Alt- (fonte)
                    </p>
                </div>
            </div>

            <div class="site-footer-bottom">
                <p>&copy; {{ date('Y') }} Daily Care. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- =============================================
         V-LIBRAS - Traducao para Libras
         ============================================= --}}
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    {{-- =============================================
         SCRIPTS DE ACESSIBILIDADE
         ============================================= --}}
    <script src="{{ asset('js/acessibilidade.js') }}"></script>

    @stack('scripts')
</body>
</html>
