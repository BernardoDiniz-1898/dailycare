@extends('layouts.app')

@section('titulo', 'Painel da Clinica')

@section('conteudo')
<div class="card" style="max-width:640px; margin:0 auto; padding:48px 32px; text-align:center;">
    <div style="font-size:3.5rem; margin-bottom:16px; color:#009688;" aria-hidden="true"><i class="bi bi-hospital"></i></div>
    <h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text); margin-bottom:12px;">Complete o Perfil da Clinica</h1>
    <p style="color:var(--color-text-secondary); font-size:1.0625rem; margin-bottom:32px;">Para comecar a receber agendamentos, cadastre as informacoes da sua clinica.</p>
    <a href="{{ route('clinica.perfil.create') }}" class="btn btn-primary" style="display:inline-flex;">
        <i class="bi bi-building-add" aria-hidden="true"></i> Cadastrar Clinica
    </a>
</div>
@endsection
