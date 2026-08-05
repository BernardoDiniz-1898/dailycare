<form method="POST" action="{{ route('login') }}" class="card" style="padding:32px; box-shadow:0 8px 24px rgba(0,0,0,0.06);" aria-label="Formulario de login">
    @csrf

    <div class="form-group" style="margin-bottom:20px;">
        <label for="email" class="form-label">
            E-mail <span class="required" aria-label="obrigatorio">*</span>
        </label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               class="form-input"
               aria-describedby="email-erro"
               autocomplete="email"
               placeholder="seu@email.com">
        @error('email')
            <p id="email-erro" class="form-error" role="alert">
                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-group" style="margin-bottom:20px;">
        <label for="senha" class="form-label">
            Senha <span class="required" aria-label="obrigatorio">*</span>
        </label>
        <div class="password-wrapper">
            <input type="password" id="senha" name="senha" required
                   class="form-input"
                   autocomplete="current-password"
                   placeholder="Sua senha">
            <button type="button" class="password-toggle" onclick="alternarVisibilidadeSenha(this)"
                    aria-label="Mostrar senha" aria-pressed="false">
                <i class="bi bi-eye" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div style="margin-bottom:24px;">
        <label class="form-check" style="border:none; padding:0; min-height:auto;">
            <input type="checkbox" id="remember" name="remember"
                   style="width:18px; height:18px; accent-color:#009688;">
            <span style="font-size:0.9375rem; color:var(--color-text-secondary);">Lembrar de mim</span>
        </label>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;">
        Entrar
    </button>
</form>

<script>
function alternarVisibilidadeSenha(botao) {
    const wrapper = botao.closest('.password-wrapper');
    const input = wrapper.querySelector('input');
    const exibindo = input.type === 'text';

    input.type = exibindo ? 'password' : 'text';
    const icone = botao.querySelector('i');
    icone.className = exibindo ? 'bi bi-eye' : 'bi bi-eye-slash';
    botao.setAttribute('aria-pressed', String(!exibindo));
    botao.setAttribute('aria-label', exibindo ? 'Mostrar senha' : 'Ocultar senha');
    input.focus();
}
</script>
