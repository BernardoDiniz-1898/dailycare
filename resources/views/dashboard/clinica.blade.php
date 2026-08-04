@extends('layouts.app')

@section('titulo', 'Painel da Clinica')

@section('conteudo')
<h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text); margin-bottom:32px;">
    <i class="bi bi-hospital" aria-hidden="true"></i> Painel da Clinica
</h1>

{{-- Estatisticas --}}
<section aria-label="Resumo de agendamentos" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:20px; margin-bottom:32px;">
    <div class="card" style="padding:24px; text-align:center;">
        <p style="font-size:2.5rem; font-weight:800; color:#92400E;">{{ $pendentes }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Pendentes</p>
    </div>
    <div class="card" style="padding:24px; text-align:center;">
        <p style="font-size:2.5rem; font-weight:800; color:#047857;">{{ $confirmados }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Confirmados</p>
    </div>
    <div class="card" style="padding:24px; text-align:center;">
        <p style="font-size:2.5rem; font-weight:800; color:#009688;">{{ $agendamentos->count() }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Total</p>
    </div>
</section>

<div style="margin-bottom:32px;">
    <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-secondary">
        <i class="bi bi-pencil-fill" aria-hidden="true"></i> Editar Perfil da Clinica
    </a>
</div>

<h2 style="font-size:1.5rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
    <i class="bi bi-clipboard-data" aria-hidden="true"></i> Agendamentos
</h2>

@if ($agendamentos->count() > 0)
    <div style="display:flex; flex-direction:column; gap:16px;">
        @foreach ($agendamentos as $agendamento)
            <article class="card" style="padding:24px;" aria-label="Agendamento de {{ $agendamento->paciente->nome }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                    <div>
                        <h3 style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin-bottom:4px;">
                            {{ $agendamento->paciente->nome }}
                        </h3>
                        <p style="color:var(--color-text-secondary); font-size:0.9375rem;">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            {{ substr($agendamento->hora, 0, 5) }}
                        </p>
                        @if ($agendamento->observacao_paciente)
                            <p style="color:var(--color-text-secondary); font-size:0.875rem; margin-top:8px;">{{ $agendamento->observacao_paciente }}</p>
                        @endif
                    </div>
                    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                        <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                        @if ($agendamento->status === 'solicitado')
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="confirmado">
                                <button type="submit" class="btn btn-success btn-sm">Confirmar</button>
                            </form>
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="recusado">
                                <button type="submit" class="btn btn-danger btn-sm">Recusar</button>
                            </form>
                        @endif
                        @if ($agendamento->status === 'confirmado')
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="concluido">
                                <button type="submit" class="btn btn-primary btn-sm">Concluido</button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="card" style="padding:64px 32px; text-align:center;">
        <div style="font-size:4rem; margin-bottom:16px; color:#D1D5DB;" aria-hidden="true"><i class="bi bi-clipboard-data"></i></div>
        <p style="color:var(--color-text-secondary); font-size:1.125rem;">Nenhum agendamento encontrado.</p>
    </div>
@endif
@endsection
