@extends('layouts.app')

@section('titulo', 'Entrar')

@section('conteudo')
<div style="max-width:440px; margin:0 auto; padding-top:48px;">
    <div style="text-align:center; margin-bottom:40px;">
        <div style="width:64px; height:64px; background:#E0F2F1; border-radius:16px; display:inline-flex; align-items:center; justify-content:center; font-size:2rem; margin-bottom:16px;">
            <span aria-hidden="true">&#x1F511;</span>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">Entrar na sua conta</h1>
        <p style="color:#6B7280; margin-top:8px;">Acesse o marketplace de fisioterapia acessivel</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="card" style="padding:32px;" aria-label="Formulario de login">
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
                    <span aria-hidden="true">&#x26A0;</span> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="senha" class="form-label">
                Senha <span class="required" aria-label="obrigatorio">*</span>
            </label>
            <input type="password" id="senha" name="senha" required
                   class="form-input"
                   autocomplete="current-password"
                   placeholder="Sua senha">
        </div>

        <div style="margin-bottom:24px;">
            <label class="form-check" style="border:none; padding:0; min-height:auto;">
                <input type="checkbox" id="remember" name="remember"
                       style="width:18px; height:18px; accent-color:#009688;">
                <span style="font-size:0.9375rem; color:#4B5563;">Lembrar de mim</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">
            Entrar
        </button>

        <p style="text-align:center; margin-top:24px; font-size:0.9375rem; color:#6B7280;">
            Nao tem conta?
            <a href="{{ route('register') }}" style="color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px;">
                Cadastre-se
            </a>
        </p>
    </form>
</div>
@endsection
