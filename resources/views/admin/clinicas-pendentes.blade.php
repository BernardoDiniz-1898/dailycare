@extends('layouts.app')

@section('titulo', 'Clinicas Pendentes')

@section('conteudo')
<a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <span aria-hidden="true">&#x2190;</span> Voltar ao Painel
</a>

<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:32px;">
    <span aria-hidden="true">&#x2705;</span> Clinicas Pendentes de Aprovacao
</h1>

@if ($clinicas->count() > 0)
    <div style="display:flex; flex-direction:column; gap:20px;">
        @foreach ($clinicas as $clinica)
            <article class="card" style="padding:28px;" aria-label="Clinica {{ $clinica->nome_fantasia }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:20px;">
                    <div>
                        <h2 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:4px;">{{ $clinica->nome_fantasia }}</h2>
                        <p style="color:#6B7280; font-size:0.9375rem;">{{ $clinica->razao_social }}</p>
                        <p style="color:#6B7280; font-size:0.875rem; margin-top:4px;">
                            CNPJ: {{ $clinica->cnpj }} | {{ $clinica->cidade }} - {{ $clinica->estado }}
                        </p>
                        <p style="color:#6B7280; font-size:0.875rem;">Responsavel: {{ $clinica->usuario->nome }}</p>
                        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:12px;">
                            @foreach ($clinica->especialidades as $esp)
                                <span class="badge badge-blue">{{ $esp->nome }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div style="display:flex; gap:12px;">
                        <form method="POST" action="{{ route('admin.clinicas.aprovar', $clinica) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Aprovar esta clinica?')">
                                <span aria-hidden="true">&#x2713;</span> Aprovar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.clinicas.rejeitar', $clinica) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Rejeitar esta clinica?')">
                                <span aria-hidden="true">&#x2717;</span> Rejeitar
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="card" style="padding:64px 32px; text-align:center;">
        <div style="font-size:4rem; margin-bottom:16px; color:#047857;" aria-hidden="true">&#x2705;</div>
        <p style="color:#6B7280; font-size:1.125rem;">Nenhuma clinica pendente de aprovacao.</p>
    </div>
@endif
@endsection
