@extends('layouts.app')

@section('titulo', 'Cadastrar')

@section('conteudo')
<div style="max-width:520px; margin:0 auto; padding-top:48px;">
    <div style="text-align:center; margin-bottom:40px;">
        <div style="width:64px; height:64px; background:#E0F2F1; border-radius:16px; display:inline-flex; align-items:center; justify-content:center; font-size:2rem; margin-bottom:16px;">
            <span aria-hidden="true">&#x2795;</span>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">Criar sua conta</h1>
        <p style="color:#6B7280; margin-top:8px;">Escolha o tipo de conta para comecar</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="card" style="padding:32px;" aria-label="Formulario de cadastro">
        @csrf

        {{-- Tipo de Conta --}}
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
                           {{ old('role') === 'clinica' ? 'checked' : '' }}
                           style="width:20px; height:20px; accent-color:#009688;">
                    <div>
                        <span style="font-weight:700; color:#111827; display:block;">Clinica</span>
                        <span style="font-size:0.8125rem; color:#6B7280;">Sou uma clinica</span>
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

        <div class="form-group" style="margin-bottom:20px;">
            <label for="nome" class="form-label">
                Nome Completo <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                   class="form-input" autocomplete="name" placeholder="Seu nome completo">
            @error('nome') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="email" class="form-label">
                E-mail <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="form-input" autocomplete="email" placeholder="seu@email.com">
            @error('email') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="cpf" class="form-label">
                CPF <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required
                   class="form-input" placeholder="000.000.000-00" maxlength="14">
            @error('cpf') <p class="form-error" role="alert"><span aria-hidden="true">&#x26A0;</span> {{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="telefone" class="form-label">Telefone (opcional)</label>
            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}"
                   class="form-input" autocomplete="tel" placeholder="(00) 00000-0000">
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
