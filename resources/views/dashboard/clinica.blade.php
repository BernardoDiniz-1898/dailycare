@extends('layouts.app')

@section('titulo', 'Painel da Clinica')

@section('conteudo')
{{-- Cabecalho --}}
<div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:32px;">
    <div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text);">
            <i class="bi bi-hospital" aria-hidden="true"></i> Painel da Clinica
        </h1>
        <p style="color:var(--color-text-secondary); margin-top:4px;">
            {{ $clinica->nome_fantasia }}
            @if ($clinica->preco_sessao)
                <span style="color:var(--color-text-secondary);">
                    &middot; <i class="bi bi-cash-coin" aria-hidden="true"></i>
                    R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}/sessao
                </span>
            @endif
            &middot;
            <span class="badge {{ $clinica->status === 'aprovada' ? 'badge-green' : 'badge-yellow' }}">
                {{ $clinica->status === 'aprovada' ? 'Aprovada' : ucfirst($clinica->status) }}
            </span>
        </p>
    </div>
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        @if ($clinica->status === 'aprovada')
            <a href="{{ route('clinicas.show', $clinica) }}" class="btn btn-primary">
                <i class="bi bi-eye-fill" aria-hidden="true"></i> Ver Pagina Publica
            </a>
        @endif
        <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-secondary">
            <i class="bi bi-pencil-fill" aria-hidden="true"></i> Editar Perfil
        </a>
    </div>
</div>

{{-- Estatisticas --}}
<section aria-label="Resumo de agendamentos" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:16px; margin-bottom:32px;">
    <a href="{{ route('dashboard', ['status' => 'solicitado']) }}" class="card card-link" style="padding:20px; text-align:center;">
        <p style="font-size:2rem; font-weight:800; color:var(--color-warning); margin:0;">{{ $contagem['solicitado'] }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Solicitados</p>
    </a>
    <a href="{{ route('dashboard', ['status' => 'confirmado']) }}" class="card card-link" style="padding:20px; text-align:center;">
        <p style="font-size:2rem; font-weight:800; color:var(--color-success); margin:0;">{{ $contagem['confirmado'] }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Confirmados</p>
    </a>
    <a href="{{ route('dashboard', ['status' => 'concluido']) }}" class="card card-link" style="padding:20px; text-align:center;">
        <p style="font-size:2rem; font-weight:800; color:var(--color-primary); margin:0;">{{ $contagem['concluido'] }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Concluidos</p>
    </a>
    <a href="{{ route('dashboard', ['status' => 'recusado']) }}" class="card card-link" style="padding:20px; text-align:center;">
        <p style="font-size:2rem; font-weight:800; color:var(--color-danger); margin:0;">{{ $contagem['recusado'] }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Recusados</p>
    </a>
    <a href="{{ route('dashboard', ['status' => 'cancelado']) }}" class="card card-link" style="padding:20px; text-align:center;">
        <p style="font-size:2rem; font-weight:800; color:#6B7280; margin:0;">{{ $contagem['cancelado'] }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Cancelados</p>
    </a>
    <div class="card" style="padding:20px; text-align:center; border-color:#F3E8C8; background:var(--color-accent-light);">
        <p style="font-size:2rem; font-weight:800; color:#8A6D1D; margin:0;">
            R$ {{ number_format($receitaEstimada, 2, ',', '.') }}
        </p>
        <p style="color:var(--color-text-secondary); font-weight:600; margin-top:4px;">Receita estimada</p>
    </div>
</section>

{{-- Filtro por status --}}
@php
    $abas = [
        '' => ['label' => 'Todos', 'contagem' => $contagem['todos'], 'icone' => 'bi-columns-gap'],
        'solicitado' => ['label' => 'Solicitados', 'contagem' => $contagem['solicitado'], 'icone' => 'bi-hourglass-split'],
        'confirmado' => ['label' => 'Confirmados', 'contagem' => $contagem['confirmado'], 'icone' => 'bi-check2-circle'],
        'concluido' => ['label' => 'Concluidos', 'contagem' => $contagem['concluido'], 'icone' => 'bi-flag-fill'],
        'recusado' => ['label' => 'Recusados', 'contagem' => $contagem['recusado'], 'icone' => 'bi-x-circle'],
        'cancelado' => ['label' => 'Cancelados', 'contagem' => $contagem['cancelado'], 'icone' => 'bi-slash-circle'],
    ];
@endphp

<nav aria-label="Filtrar agendamentos por status" style="margin-bottom:24px;">
    <div role="tablist" aria-label="Abas de status dos agendamentos"
         style="display:flex; gap:8px; flex-wrap:wrap; border-bottom:2px solid #E5E7EB; padding-bottom:12px;">
        @foreach ($abas as $valor => $aba)
            <a href="{{ route('dashboard', $valor ? ['status' => $valor] : []) }}"
               role="tab"
               aria-selected="{{ $statusAtivo === ($valor ?: null) ? 'true' : 'false' }}"
               @if ($statusAtivo === ($valor ?: null)) aria-current="page" @endif
               style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:9999px;
                      font-weight:700; font-size:0.875rem; text-decoration:none;
                      color:{{ $statusAtivo === ($valor ?: null) ? '#FFFFFF' : 'var(--color-text-secondary)' }};
                      background:{{ $statusAtivo === ($valor ?: null) ? '#009688' : 'transparent' }};">
                <i class="{{ $aba['icone'] }}" aria-hidden="true"></i>
                {{ $aba['label'] }}
                <span class="badge {{ $statusAtivo === ($valor ?: null) ? 'badge-yellow' : 'badge-gray' }}">{{ $aba['contagem'] }}</span>
            </a>
        @endforeach
    </div>
</nav>

{{-- Lista de agendamentos --}}
<h2 style="font-size:1.375rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
    <i class="bi bi-clipboard-data" aria-hidden="true"></i> Agendamentos
    @if ($statusAtivo)
        <span class="badge {{ $agendamentosFiltrados->first()?->statusCor() ?? 'badge-gray' }}">
            {{ collect($abas)->get($statusAtivo)['label'] }}
        </span>
    @endif
</h2>

@if ($agendamentosFiltrados->count() > 0)
    <div style="display:flex; flex-direction:column; gap:16px;">
        @foreach ($agendamentosFiltrados as $agendamento)
            <article class="card" style="padding:24px;" aria-label="Agendamento de {{ $agendamento->paciente->nome }}">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                    <div style="flex:1; min-width:240px;">
                        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:4px;">
                            <h3 style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin:0;">
                                {{ $agendamento->paciente->nome }}
                            </h3>
                            <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                        </div>

                        <p style="color:var(--color-text-secondary); font-size:0.9375rem;">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}
                            &nbsp;<i class="bi bi-clock" aria-hidden="true"></i>
                            {{ substr($agendamento->hora, 0, 5) }}
                        </p>

                        <div style="display:flex; gap:16px; flex-wrap:wrap; margin-top:8px; font-size:0.875rem; color:var(--color-text-secondary);">
                            @if ($agendamento->paciente->telefone)
                                <span><i class="bi bi-telephone" aria-hidden="true"></i> {{ $agendamento->paciente->telefone }}</span>
                            @endif
                            @if ($agendamento->paciente->condicao)
                                <span><i class="bi bi-heart-pulse" aria-hidden="true"></i> {{ $agendamento->paciente->condicao }}</span>
                            @endif
                            @if ($clinica->preco_sessao)
                                <span><i class="bi bi-cash-coin" aria-hidden="true"></i> R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}</span>
                            @endif
                        </div>

                        @if ($agendamento->observacao_paciente)
                            <p style="color:var(--color-text-secondary); font-size:0.875rem; margin-top:12px; padding:10px 14px; background:#F3F4F6; border-radius:8px;">
                                <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                                {{ $agendamento->observacao_paciente }}
                            </p>
                        @endif

                        @if ($agendamento->observacao_clinica)
                            <p style="color:var(--color-primary-dark); font-size:0.875rem; margin-top:8px; padding:10px 14px; background:var(--color-primary-light); border-radius:8px;">
                                <i class="bi bi-reply-fill" aria-hidden="true"></i>
                                {{ $agendamento->observacao_clinica }}
                            </p>
                        @endif
                    </div>

                    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                        @if ($agendamento->status === 'solicitado')
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="confirmado">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> Confirmar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="recusado">
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Recusar o agendamento de {{ $agendamento->paciente->nome }}?')">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i> Recusar
                                </button>
                            </form>
                        @endif
                        @if ($agendamento->status === 'confirmado')
                            <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="concluido">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-flag-fill" aria-hidden="true"></i> Concluido
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
        <div style="font-size:4rem; margin-bottom:16px; color:#D1D5DB;" aria-hidden="true"><i class="bi bi-clipboard-data"></i></div>
        <p style="color:var(--color-text-secondary); font-size:1.125rem; margin-bottom:16px;">
            Nenhum agendamento {{ $statusAtivo ? 'com este status' : 'encontrado' }}.
        </p>
        @if (!$statusAtivo)
            <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Divulgue seus horarios
            </a>
        @endif
    </div>
@endif
@endsection
