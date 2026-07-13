@extends('layouts.app')

@section('titulo', 'Meus Agendamentos')

@section('conteudo')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:32px;">
    <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">
        <span aria-hidden="true">&#x1F4CA;</span> Meus Agendamentos
    </h1>
    <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
        <span aria-hidden="true">&#x1F50D;</span> Buscar Clinicas
    </a>
</div>

@if ($agendamentos->count() > 0)
    <div style="display:flex; flex-direction:column; gap:16px;">
        @foreach ($agendamentos as $agendamento)
            <article class="card" style="padding:24px;" aria-label="Agendamento com {{ $agendamento->clinica->nome_fantasia }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                    <div>
                        <h2 style="font-size:1.125rem; font-weight:700; margin-bottom:4px;">
                            <a href="{{ route('clinicas.show', $agendamento->clinica) }}" style="color:#1A56DB; text-decoration:none;">
                                {{ $agendamento->clinica->nome_fantasia }}
                            </a>
                        </h2>
                        <p style="color:#4B5563; font-size:0.9375rem;">
                            <span aria-hidden="true">&#x1F4C5;</span>
                            {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}
                            <span aria-hidden="true">&#x1F552;</span>
                            {{ substr($agendamento->hora, 0, 5) }}
                        </p>
                        @if ($agendamento->observacao_paciente)
                            <p style="color:#6B7280; font-size:0.875rem; margin-top:8px;">{{ $agendamento->observacao_paciente }}</p>
                        @endif
                        @if ($agendamento->observacao_clinica)
                            <p style="color:#1A56DB; font-size:0.875rem; margin-top:8px; padding:8px 12px; background:#EFF6FF; border-radius:8px;">
                                <strong>Resposta da clinica:</strong> {{ $agendamento->observacao_clinica }}
                            </p>
                        @endif
                    </div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                        @if ($agendamento->status === 'solicitado')
                            <form method="POST" action="{{ route('agendamentos.destroy', $agendamento) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tem certeza que deseja cancelar?')">
                                    Cancelar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="card" style="padding:64px 32px; text-align:center;">
        <div style="font-size:4rem; margin-bottom:16px; color:#D1D5DB;" aria-hidden="true">&#x1F4C5;</div>
        <p style="color:#6B7280; font-size:1.125rem; margin-bottom:16px;">Voce ainda nao tem agendamentos.</p>
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
            <span aria-hidden="true">&#x1F50D;</span> Buscar clinicas de fisioterapia
        </a>
    </div>
@endif
@endsection
