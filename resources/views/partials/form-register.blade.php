<form method="POST" action="{{ route('register') }}" class="card" style="padding:32px; box-shadow:0 8px 24px rgba(0,0,0,0.06);" aria-label="Formulario de cadastro">

    {{-- Token CSRF gerado pelo Laravel --}}
    @csrf

    {{-- Campo que define o tipo de conta escolhida: paciente ou fisioterapeuta --}}
    <fieldset style="border:none; padding:0; margin:0 0 24px 0;">

        <legend class="form-label" style="margin-bottom:12px; font-size:1rem;">Tipo de Conta</legend>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

            <label id="label-paciente" class="form-check" style="{{ old('role', 'paciente') === 'paciente' ? 'border-color:#009688; background:#E0F2F1;' : '' }}">
                <input type="radio" name="role" value="paciente"
                    {{ old('role', 'paciente') === 'paciente' ? 'checked' : '' }}
                    style="width:20px; height:20px; accent-color:#009688;"
                    onchange="alternarCampos('paciente')">
                <div>
                    <span style="font-weight:700; color:#111827; display:block;">Paciente</span>
                    <span style="font-size:0.8125rem; color:#6B7280;">Busco clinicas acessiveis</span>
                </div>
            </label>

            <label id="label-clinica" class="form-check" style="{{ old('role') === 'fisioterapeuta' ? 'border-color:#009688; background:#E0F2F1;' : '' }}">
                <input type="radio" name="role" value="fisioterapeuta"
                    {{ old('role') === 'fisioterapeuta' ? 'checked' : '' }}
                    style="width:20px; height:20px; accent-color:#009688;"
                    onchange="alternarCampos('fisioterapeuta')">
                <div>
                    <span style="font-weight:700; color:#111827; display:block;">Fisioterapeuta</span>
                    <span style="font-size:0.8125rem; color:#6B7280;">Quero cadastrar minha clinica</span>
                </div>
            </label>

        </div>

        @error('role')
            <p class="form-error" style="margin-top:8px;" role="alert">
                <span aria-hidden="true">&#x26A0;</span> {{ $message }}
            </p>
        @enderror

    </fieldset>

    <hr style="border:none; border-top:1px solid #E5E7EB; margin:0 0 24px 0;">

    {{-- ========================================== --}}
    {{-- BLOCO 1: CAMPOS EXCLUSIVOS DO PACIENTE     --}}
    {{-- ========================================== --}}
    <div id="bloco-paciente">

        <div class="form-group" style="margin-bottom:20px;">
            <label for="nome" class="form-label">
                Nome Completo <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                class="form-input input-paciente" autocomplete="name" placeholder="Seu nome completo">
            @error('nome') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="cpf" class="form-label">
                CPF <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required
                class="form-input input-paciente" placeholder="000.000.000-00" maxlength="14">
            @error('cpf') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="endereco" class="form-label">
                Endereço (para encontrar clínicas próximas)
            </label>
            <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}"
                class="form-input" placeholder="Sua rua, número e bairro">
            @error('endereco') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- BLOCO 2: CAMPOS EXCLUSIVOS DA CLÍNICA      --}}
    {{-- ========================================== --}}
    <div id="bloco-clinica" style="display: none;">

        <div class="form-group" style="margin-bottom:20px;">
            <label for="nome_responsavel" class="form-label">
                Nome do Responsável <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="nome_responsavel" name="nome_responsavel" value="{{ old('nome_responsavel') }}"
                class="form-input input-clinica" placeholder="Nome do representante legal">
            @error('nome_responsavel') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="razao_social" class="form-label">
                Razão Social <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}"
                class="form-input input-clinica" placeholder="Razão social da empresa">
            @error('razao_social') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="nome_fantasia" class="form-label">
                Nome Fantasia <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}"
                class="form-input input-clinica" placeholder="Nome comercial da clínica">
            @error('nome_fantasia') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="cnpj" class="form-label">
                CNPJ <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}"
                class="form-input input-clinica" placeholder="00.000.000/0001-00" maxlength="18">
            @error('cnpj') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        {{-- Endereço Comercial Completo da Clínica --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
            <div>
                <label for="cep" class="form-label">CEP *</label>
                <input type="text" id="cep" name="cep" value="{{ old('cep') }}" class="form-input input-clinica" placeholder="00000-000">
                @error('cep') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
            </div>
            <div>
                <label for="numero" class="form-label">Número *</label>
                <input type="text" id="numero" name="numero" value="{{ old('numero') }}" class="form-input input-clinica" placeholder="Ex: 123">
                @error('numero') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="logradouro" class="form-label">Logradouro / Rua *</label>
            <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') }}" class="form-input input-clinica" placeholder="Av. Principal, Rua...">
            @error('logradouro') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:20px;">
            <div>
                <label for="bairro" class="form-label">Bairro *</label>
                <input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}" class="form-input input-clinica" placeholder="Bairro">
                @error('bairro') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
            </div>
            <div>
                <label for="cidade" class="form-label">Cidade *</label>
                <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}" class="form-input input-clinica" placeholder="Cidade">
                @error('cidade') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
            </div>
            <div>
                <label for="estado" class="form-label">UF *</label>
                <input type="text" id="estado" name="estado" value="{{ old('estado') }}" class="form-input input-clinica" placeholder="Ex: SP" maxlength="2">
                @error('estado') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- BLOCO 3: CAMPOS COMUNS (E-MAIL, SENHAS)    --}}
    {{-- ========================================== --}}
    <div class="form-group" style="margin-bottom:20px;">
        <label for="email" class="form-label">
            E-mail <span class="required" aria-label="obrigatorio">*</span>
        </label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required
            class="form-input" autocomplete="email" placeholder="seu@email.com">
        @error('email') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
    </div>

    <div class="form-group" style="margin-bottom:20px;">
        <label for="telefone" class="form-label">Telefone (opcional)</label>
        <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
            class="form-input" autocomplete="tel" placeholder="(00) 00000-0000">
        @error('telefone') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
    </div>

    <div class="form-group" style="margin-bottom:20px;">
        <label for="senha" class="form-label">
            Senha <span class="required" aria-label="obrigatorio">*</span>
        </label>
        <input type="password" id="senha" name="senha" required
            class="form-input" autocomplete="new-password" placeholder="Minimo 8 caracteres">
        @error('senha') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
    </div>

    <div class="form-group" style="margin-bottom:24px;">
        <label for="senha_confirmation" class="form-label">
            Confirmar Senha <span class="required" aria-label="obrigatorio">*</span>
        </label>
        <input type="password" id="senha_confirmation" name="senha_confirmation" required
            class="form-input" autocomplete="new-password" placeholder="Repita a senha">
    </div>

    {{-- Botão que submete o formulário --}}
    <button type="submit" class="btn btn-primary" style="width:100%;">
        Criar Conta
    </button>

</form>

{{-- SCRIPT PARA ALTERNÂNCIA DINÂMICA DOS BLOCOS E REGRAS --}}
<script>
function alternarCampos(role) {
    const blocoPaciente = document.getElementById('bloco-paciente');
    const blocoClinica = document.getElementById('bloco-clinica');
    const labelPaciente = document.getElementById('label-paciente');
    const labelClinica = document.getElementById('label-clinica');

    const inputsPaciente = blocoPaciente.querySelectorAll('.input-paciente');
    const inputsClinica = blocoClinica.querySelectorAll('.input-clinica');

    if (role === 'fisioterapeuta') {
        blocoPaciente.style.display = 'none';
        blocoClinica.style.display = 'block';

        labelClinica.style.borderColor = '#009688';
        labelClinica.style.background = '#E0F2F1';
        labelPaciente.style.borderColor = '';
        labelPaciente.style.background = '';

        inputsPaciente.forEach(input => input.required = false);
        inputsClinica.forEach(input => input.required = true);
    } else {
        blocoPaciente.style.display = 'block';
        blocoClinica.style.display = 'none';

        labelPaciente.style.borderColor = '#009688';
        labelPaciente.style.background = '#E0F2F1';
        labelClinica.style.borderColor = '';
        labelClinica.style.background = '';

        inputsClinica.forEach(input => input.required = false);
        inputsPaciente.forEach(input => input.required = true);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const roleChecked = document.querySelector('input[name="role"]:checked');
    if (roleChecked) {
        alternarCampos(roleChecked.value);
    }
});
</script>
