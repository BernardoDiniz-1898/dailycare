{{-- inicio do cadastro (form de criar conta, paciente ou clinica) --}}
@extends('layouts.app')

{{-- Define o título da página que será usado pelo layout principal --}}
@section('titulo', 'Cadastrar')

{{-- Marca o conteúdo que será inserido no layout principal --}}
@section('conteudo')
<div style="max-width:520px; margin:0 auto; padding:56px 0;">
    <div style="text-align:center; margin-bottom:32px;">

        <div style="width:72px; height:72px; background:#E0F2F1; border-radius:20px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
            <i class="bi bi-person-plus-fill" style="font-size:1.75rem; color:#009688;" aria-hidden="true"></i>
        </div>

        <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">Criar sua conta</h1>

        <p style="color:#6B7280; margin-top:8px;">Escolha o tipo de conta para começar</p>

    </div>

    {{-- Links de navegação entre as páginas de login e cadastro, conectados às rotas do Laravel --}}
    <div class="auth-tabs" role="tablist" aria-label="Alternar entre entrar e cadastrar">

        <a href="{{ route('login') }}" class="auth-tab" role="tab" aria-selected="false">Entrar</a>
        <a href="{{ route('register') }}" class="auth-tab auth-tab-ativo" role="tab" aria-selected="true">Criar conta</a>

    </div>

    {{-- Formulário que envia os dados para a rota de cadastro do backend via POST --}}
    <form method="POST" action="{{ route('register') }}" class="card" style="padding:32px; box-shadow:0 8px 24px rgba(0,0,0,0.06);" aria-label="Formulario de cadastro">

        {{-- Token CSRF gerado pelo Laravel para proteger o envio do formulário --}}
        @csrf

        {{-- Campo que define o tipo de conta escolhida: paciente ou clínica --}}
        <fieldset style="border:none; padding:0; margin:0 0 24px 0;">

            <legend class="form-label" style="margin-bottom:12px; font-size:1rem;">Tipo de Conta</legend>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                <label class="form-check" style="{{ old('role', 'paciente') === 'paciente' ? 'border-color:#009688; background:#E0F2F1;' : '' }}">
                    <input type="radio" name="role" value="paciente"
                        {{ old('role', 'paciente') === 'paciente' ? 'checked' : '' }}
                        style="width:20px; height:20px; accent-color:#009688;">
                    <div>
                        <span style="font-weight:700; color:#111827; display:block;">Paciente</span>
                        <span style="font-size:0.8125rem; color:#6B7280;">Busco clinicas acessiveis</span>
                    </div>
                </label>

                <label class="form-check" style="{{ old('role') === 'clinica' ? 'border-color:#009688; background:#E0F2F1;' : '' }}">
                    <input type="radio" name="role" value="clinica"
                        {{ old('role') === 'clinica' ? 'checked' : ''  }}
                        style="width:20px; height:20px; accent-color:#009688;" href="{{ route('clinica.perfil.create') }}">
                    <div>
                        <span style="font-weight:700; color:#111827; display:block;">Clinica</span>
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

        {{-- dados pessoais --}}
        <div class="form-group" style="margin-bottom:20px;">

            <label for="nome" class="form-label">
                Nome Completo <span class="required" aria-label="obrigatorio">*</span>
            </label>

            {{-- Campo que armazena o nome do usuário; será enviado ao backend para cadastro --}}
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                class="form-input" autocomplete="name" placeholder="Seu nome completo">

            {{-- Exibe mensagem de erro de validação retornada pelo backend para este campo --}}
            @error('nome') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">

            <label for="email" class="form-label">
                E-mail <span class="required" aria-label="obrigatorio">*</span>
            </label>
            {{-- Campo que armazena o e-mail do usuário; usado pelo backend para identificar a conta --}}
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="form-input" autocomplete="email" placeholder="seu@email.com">

            {{-- Exibe mensagem de erro de validação para o e-mail --}}
            @error('email') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">

            <label for="cpf" class="form-label">
                CPF <span class="required" aria-label="obrigatorio">*</span>
            </label>

            {{-- Campo que armazena o CPF; normalmente é usado pelo backend para validação e identificação do usuário --}}
            <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required

                class="form-input" placeholder="000.000.000-00" maxlength="14">
            {{-- Exibe erro de validação para o CPF --}}

            @error('cpf') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">

            <label for="telefone" class="form-label">Telefone (opcional)</label>
            {{-- Campo opcional que armazena o telefone do usuário para uso posterior pelo backend --}}
            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
                class="form-input" autocomplete="tel" placeholder="(00) 00000-0000">

        </div>

        {{-- Campo de senha que será tratado pelo backend para criar a conta com segurança --}}
        <div class="form-group" style="margin-bottom:20px;">

            <label for="senha" class="form-label">
                Senha <span class="required" aria-label="obrigatorio">*</span>
            </label>

            {{-- Campo sensível que guarda a senha; o backend deve criptografá-la antes de salvar --}}
            <input type="password" id="senha" name="senha" required
                class="form-input" autocomplete="new-password" placeholder="Minimo 8 caracteres">

            {{-- Exibe erro de validação para a senha --}}
            @error('senha') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror

        </div>

        <div class="form-group" style="margin-bottom:24px;">

            <label for="senha_confirmation" class="form-label">
                Confirmar Senha <span class="required" aria-label="obrigatorio">*</span>
            </label>

            {{-- Campo usado para confirmar a senha; o backend valida se os dois campos coincidem --}}
            <input type="password" id="senha_confirmation" name="senha_confirmation" required
                class="form-input" autocomplete="new-password" placeholder="Repita a senha">

        </div>

        {{-- Botão que submete o formulário para o controller responsável pelo cadastro --}}
        <button type="submit" class="btn btn-primary" style="width:100%;">
            Criar Conta
        </button>

        <p style="text-align:center; margin-top:24px; font-size:0.9375rem; color:#6B7280;">
            Ja tem conta?
            <a href="{{ route('login') }}" style="color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px;">
                Entrar
            </a>
        </p>

    </form>

</div>
@endsection
{{-- fim do cadastro --}}

