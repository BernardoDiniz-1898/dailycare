@extends('layouts.app')

@section('titulo', 'Entrar')

@section('conteudo')
<div style="max-width:440px; margin:0 auto; padding:56px 0;">
    <div style="text-align:center; margin-bottom:32px;">
        <div style="width:72px; height:72px; background:#E0F2F1; border-radius:20px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
            <i class="bi bi-key-fill" style="font-size:1.75rem; color:#009688;" aria-hidden="true"></i>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text);">Entrar na sua conta</h1>
        <p style="color:var(--color-text-secondary); margin-top:8px;">Acesse o marketplace de fisioterapia acessivel</p>
    </div>

    <div class="auth-tabs" role="tablist" aria-label="Alternar entre entrar e cadastrar">
        <a href="{{ route('login') }}" class="auth-tab auth-tab-ativo" role="tab" aria-selected="true">Entrar</a>
        <a href="{{ route('register') }}" class="auth-tab" role="tab" aria-selected="false">Criar conta</a>
    </div>

    @include('partials.form-login')

    <p style="text-align:center; margin-top:24px; font-size:0.9375rem; color:var(--color-text-secondary);">
        Nao tem conta?
        <a href="{{ route('register') }}" style="color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px;">
            Cadastre-se
        </a>
    </p>
</div>
@endsection
