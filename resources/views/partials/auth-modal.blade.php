{{-- Modal de Entrar/Criar conta. Reaproveita os mesmos forms/rotas das paginass --}}
<div id="auth-modal-backdrop" class="auth-modal-backdrop" aria-hidden="true">
    <div class="auth-modal" role="dialog" aria-modal="true" aria-labelledby="auth-modal-titulo">
        <button type="button" class="auth-modal-fechar" onclick="window.DailyCare.authModal.fechar()" aria-label="Fechar">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>

        <div style="text-align:center; margin-bottom:24px;">
            <div style="width:64px; height:64px; background:#E0F2F1; border-radius:18px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px;">
                <i id="auth-modal-icone" class="bi bi-key-fill" style="font-size:1.5rem; color:#009688;" aria-hidden="true"></i>
            </div>
            <h2 id="auth-modal-titulo" style="font-size:1.375rem; font-weight:800; color:#111827;">Entrar na sua conta</h2>
        </div>

        <div class="auth-tabs" role="tablist" aria-label="Alternar entre entrar e cadastrar">
            <button type="button" id="auth-modal-tab-login" class="auth-tab auth-tab-ativo" role="tab" aria-selected="true"
                    onclick="window.DailyCare.authModal.mudarAba('login')">Entrar</button>
            <button type="button" id="auth-modal-tab-registro" class="auth-tab" role="tab" aria-selected="false"
                    onclick="window.DailyCare.authModal.mudarAba('registro')">Criar conta</button>
        </div>

        <div id="auth-modal-painel-login">
            @include('partials.form-login')
            <p style="text-align:center; margin-top:20px; font-size:0.9375rem; color:#6B7280;">
                Nao tem conta?
                <button type="button" onclick="window.DailyCare.authModal.mudarAba('registro')"
                        style="background:none; border:none; padding:0; color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px; cursor:pointer;">
                    Cadastre-se
                </button>
            </p>
        </div>

        <div id="auth-modal-painel-registro" style="display:none;">
            @include('partials.form-register')
            <p style="text-align:center; margin-top:20px; font-size:0.9375rem; color:#6B7280;">
                Ja tem conta?
                <button type="button" onclick="window.DailyCare.authModal.mudarAba('login')"
                        style="background:none; border:none; padding:0; color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px; cursor:pointer;">
                    Entrar
                </button>
            </p>
        </div>
    </div>
</div>

<script>
    window.DailyCare = window.DailyCare || {};
    window.DailyCare.authModal = {
        abrir(aba) {
            document.getElementById('auth-modal-backdrop').classList.add('aberto');
            document.body.style.overflow = 'hidden';
            this.mudarAba(aba || 'login');
        },
        fechar() {
            document.getElementById('auth-modal-backdrop').classList.remove('aberto');
            document.body.style.overflow = '';
        },
        mudarAba(aba) {
            const painelLogin = document.getElementById('auth-modal-painel-login');
            const painelRegistro = document.getElementById('auth-modal-painel-registro');
            const tabLogin = document.getElementById('auth-modal-tab-login');
            const tabRegistro = document.getElementById('auth-modal-tab-registro');
            const titulo = document.getElementById('auth-modal-titulo');
            const icone = document.getElementById('auth-modal-icone');

            if (aba === 'registro') {
                painelLogin.style.display = 'none';
                painelRegistro.style.display = 'block';
                tabRegistro.classList.add('auth-tab-ativo');
                tabLogin.classList.remove('auth-tab-ativo');
                tabRegistro.setAttribute('aria-selected', 'true');
                tabLogin.setAttribute('aria-selected', 'false');
                titulo.textContent = 'Criar sua conta';
                icone.className = 'bi bi-person-plus-fill';
            } else {
                painelLogin.style.display = 'block';
                painelRegistro.style.display = 'none';
                tabLogin.classList.add('auth-tab-ativo');
                tabRegistro.classList.remove('auth-tab-ativo');
                tabLogin.setAttribute('aria-selected', 'true');
                tabRegistro.setAttribute('aria-selected', 'false');
                titulo.textContent = 'Entrar na sua conta';
                icone.className = 'bi bi-key-fill';
            }
        }
    };

    document.getElementById('auth-modal-backdrop').addEventListener('click', function (evento) {
        if (evento.target === this) {
            window.DailyCare.authModal.fechar();
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') {
            window.DailyCare.authModal.fechar();
        }
    });

    {{-- Se o form voltou com erro de validacao, reabre o modal na aba certa --}}
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            window.DailyCare.authModal.abrir('{{ old('role') !== null ? 'registro' : 'login' }}');
        });
    @endif
</script>
