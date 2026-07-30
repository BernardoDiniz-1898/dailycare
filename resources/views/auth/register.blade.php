{{-- inicio do cadastro (form de criar conta, paciente ou clinica) --}}
@extends('layouts.app')

@section('titulo', 'Cadastrar')

@section('conteudo')
<div style="max-width:520px; margin:0 auto; padding:56px 0;">
    <div style="text-align:center; margin-bottom:32px;">
        <div style="width:72px; height:72px; background:#E0F2F1; border-radius:20px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
            <i class="bi bi-person-plus-fill" style="font-size:1.75rem; color:#009688;" aria-hidden="true"></i>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">Criar sua conta</h1>
        <p style="color:#6B7280; margin-top:8px;">Escolha o tipo de conta para comecar</p>
    </div>

    <div class="auth-tabs" role="tablist" aria-label="Alternar entre entrar e cadastrar">
        <a href="{{ route('login') }}" class="auth-tab" role="tab" aria-selected="false">Entrar</a>
        <a href="{{ route('register') }}" class="auth-tab auth-tab-ativo" role="tab" aria-selected="true">Criar conta</a>
    </div>

    @include('partials.form-register')

    <p style="text-align:center; margin-top:24px; font-size:0.9375rem; color:#6B7280;">
        Ja tem conta?
        <a href="{{ route('login') }}" style="color:#009688; font-weight:600; text-decoration:underline; text-underline-offset:4px;">
            Entrar
        </a>
    </p>
</div>
@endsection
{{-- fim do cadastro --}}

