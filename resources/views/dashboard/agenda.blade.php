@extends('layouts.app')

@section('titulo', 'Minha Agenda')

@section('conteudo')

@php
    $especialidade = $clinica->especialidades->first();
    $dataFormatada = $data->locale('pt_BR')->translatedFormat('l, d [de] MMMM [de] YYYY');
    $diaAbrev = $data->locale('pt_BR')->translatedFormat('D');
    $diaNum = $data->format('d');
    $diaMes = $data->locale('pt_BR')->translatedFormat('M');
    $ehHoje = $data->isSameDay(today());
    $temHorarios = $horariosDia->count() > 0;
    $sessoesDoDia = $agendamentosDia->whereNotIn('status', ['cancelado', 'recusado'])->count();
@endphp

<div style="max-width:1024px; margin:0 auto; display:flex; flex-direction:column; gap:28px;">

    {{-- =============================================
         CABECALHO DA PAGINA
         ============================================= --}}
    <section aria-label="Cabecalho da agenda">
        <a href="{{ route('dashboard') }}"
           style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; font-size:0.875rem; text-decoration:none; margin-bottom:16px;">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar ao painel
        </a>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0 0 4px;">
                    <i class="bi bi-calendar3" style="color:#009688;" aria-hidden="true"></i> Minha Agenda
                </h1>
                <p style="color:var(--color-text-secondary); margin:0;">
                    {{ $clinica->nome_fantasia }}
                    @if ($especialidade)
                        &middot; {{ $especialidade->nome }}
                    @endif
                </p>
            </div>
            @if ($clinica->status === 'aprovada')
                <a href="{{ route('clinicas.show', $clinica) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-eye-fill" aria-hidden="true"></i> Ver Página Pública
                </a>
            @endif
        </div>
    </section>

    {{-- =============================================
         NAVEGACAO POR DIA
         ============================================= --}}
    <section aria-label="Navegar entre dias"
             style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; background:#fff; border:1px solid #F3F4F6; border-radius:16px; padding:16px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">

        <a href="{{ route('agenda', array_merge(['data' => $anterior->toDateString()], $statusAtivo ? ['status' => $statusAtivo] : [])) }}"
           class="btn btn-secondary btn-sm" aria-label="Dia anterior">
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </a>

        <a href="{{ route('agenda') }}" class="btn btn-sm {{ $ehHoje ? 'btn-primary' : 'btn-secondary' }}" aria-label="Ir para hoje">
            Hoje
        </a>

        <div style="flex:1; min-width:180px; text-align:center;">
            <div style="display:flex; align-items:center; justify-content:center; gap:12px;">
                <span aria-hidden="true"
                      style="display:inline-flex; flex-direction:column; align-items:center; justify-content:center; width:52px; height:52px; border-radius:14px; background:#E0F2F1; color:#009688; font-weight:800; font-family:var(--font-heading);">
                    <span style="font-size:0.6875rem; text-transform:uppercase;">{{ $diaAbrev }}</span>
                    <span style="font-size:1.125rem; line-height:1;">{{ $diaNum }}</span>
                </span>
                <div style="text-align:left;">
                    <strong style="color:#111827; font-size:0.9375rem; font-family:var(--font-heading); display:block;">
                        {{ $data->locale('pt_BR')->translatedFormat('MMMM [de] Y') }}
                    </strong>
                    <span style="color:var(--color-text-secondary); font-size:0.8125rem;">
                        @if ($ehHoje)
                            Hoje
                        @elseif ($data->isTomorrow())
                            Amanha
                        @elseif ($data->isYesterday())
                            Ontem
                        @else
                            {{ $dataFormatada }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <a href="{{ route('agenda', array_merge(['data' => $proxima->toDateString()], $statusAtivo ? ['status' => $statusAtivo] : [])) }}"
           class="btn btn-secondary btn-sm" aria-label="Proximo dia">
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </a>

        <form method="GET" action="{{ route('agenda') }}" style="display:flex; align-items:center; gap:8px;">
            @if ($statusAtivo)
                <input type="hidden" name="status" value="{{ $statusAtivo }}">
            @endif
            <label for="data-agenda" class="sr-only">Ir para uma data especifica</label>
            <input type="date" id="data-agenda" name="data" value="{{ $data->toDateString() }}"
                   style="padding:9px 12px; border:1px solid #D1D5DB; border-radius:10px; font-size:0.875rem; color:#111827; background:#fff;">
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="bi bi-calendar-plus" aria-hidden="true"></i> Ir
            </button>
        </form>
    </section>

    {{-- =============================================
         RESUMO DO DIA
         ============================================= --}}
    <section aria-label="Resumo do dia"
             style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:16px;">
        <div style="display:flex; flex-direction:column; justify-content:center; min-height:110px; padding:20px; background:#fff; border:1px solid #E3E9E8; border-radius:16px; box-shadow:0 1px 2px rgba(16,42,42,0.05), 0 4px 14px rgba(16,42,42,0.06);">
            <p style="font-size:1.75rem; font-weight:800; color:#009688; font-family:var(--font-heading); margin:0 0 2px;">{{ $sessoesDoDia }}</p>
            <p style="color:#6B7280; font-size:0.75rem; font-weight:600; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Sessoes do dia</p>
        </div>
        <div style="display:flex; flex-direction:column; justify-content:center; min-height:110px; padding:20px; background:#fff; border:1px solid #E3E9E8; border-radius:16px; box-shadow:0 1px 2px rgba(16,42,42,0.05), 0 4px 14px rgba(16,42,42,0.06);">
            <p style="font-size:1.75rem; font-weight:800; color:#D4AF37; font-family:var(--font-heading); margin:0 0 2px;">{{ $contagem['solicitado'] }}</p>
            <p style="color:#6B7280; font-size:0.75rem; font-weight:600; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Aguardando confirmacao</p>
        </div>
        <div style="display:flex; flex-direction:column; justify-content:center; min-height:110px; padding:20px; background:#fff; border:1px solid #E3E9E8; border-radius:16px; box-shadow:0 1px 2px rgba(16,42,42,0.05), 0 4px 14px rgba(16,42,42,0.06);">
            <p style="font-size:1.75rem; font-weight:800; color:#6B7280; font-family:var(--font-heading); margin:0 0 2px;">{{ $temHorarios ? $horariosDia->sum(fn ($h) => \Carbon\Carbon::parse($h->hora_inicio)->diffInMinutes(\Carbon\Carbon::parse($h->hora_fim)) / 60) : 0 }}</p>
            <p style="color:#6B7280; font-size:0.75rem; font-weight:600; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Horas publicadas</p>
        </div>
    </section>

    {{-- =============================================
         LINHA DO TEMPO DO DIA (HORARIOS)
         ============================================= --}}
    <section id="linha-do-tempo" aria-label="Linha do tempo dos horarios do dia">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:16px;">
            <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">
                Horarios do dia
            </h2>
            @if ($ehHoje)
                <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar disponibilidade
                </a>
            @endif
        </div>

        @if ($slots->count() > 0)
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($slots as $slot)
                    @php $agendamento = $slot['agendamento']; @endphp
                    @if ($agendamento)
                        @php $paciente = $agendamento->paciente; @endphp
                        <article class="card" style="border-left:4px solid {{ in_array($agendamento->status, ['cancelado', 'recusado']) ? '#D1D5DB' : '#009688' }}; padding:16px; border-radius:16px;" aria-label="Agendamento de {{ $paciente->nome }} as {{ $slot['hora'] }}">
                            <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                                <span style="display:inline-flex; align-items:center; gap:8px; color:#111827; font-weight:700; font-size:0.9375rem; min-width:56px;">
                                    {{ $slot['hora'] }}
                                    <span style="width:10px; height:10px; border-radius:50%; background:{{ in_array($agendamento->status, ['solicitado', 'confirmado']) ? '#22C55E' : '#D1D5DB' }}; flex-shrink:0;" aria-hidden="true"></span>
                                </span>
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#E0F2F1; color:#009688; font-weight:800; font-size:0.8125rem; flex-shrink:0;" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr($paciente->nome, 0, 2)) }}
                                </span>
                                <div style="flex:1; min-width:200px;">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                        <h3 style="font-size:0.9375rem; font-weight:700; color:#111827; margin:0;">{{ $paciente->nome }}</h3>
                                        <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                                    </div>
                                    <p style="color:#009688; font-size:0.8125rem; font-weight:600; margin:2px 0;">
                                        {{ $especialidade?->nome ?? $clinica->nome_fantasia }}
                                    </p>
                                    <p style="color:#6B7280; font-size:0.8125rem; margin:0;">
                                        <i class="bi bi-hospital" aria-hidden="true"></i> {{ $clinica->nome_fantasia }}
                                        &middot; 60 min
                                        @if ($clinica->preco_sessao)
                                            &middot; <i class="bi bi-cash-coin" aria-hidden="true"></i> R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}
                                        @endif
                                    </p>
                                    @if ($paciente->condicao)
                                        <p style="color:#6B7280; font-size:0.8125rem; margin:4px 0 0;">
                                            <i class="bi bi-heart-pulse" aria-hidden="true"></i> {{ $paciente->condicao }}
                                        </p>
                                    @endif
                                    @if ($agendamento->observacao_paciente)
                                        <p style="color:#6B7280; font-size:0.8125rem; margin:4px 0 0; padding:8px 12px; background:#F3F4F6; border-radius:8px;">
                                            <i class="bi bi-chat-left-text" aria-hidden="true"></i> {{ $agendamento->observacao_paciente }}
                                        </p>
                                    @endif
                                </div>
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
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
                                                    onclick="return confirm('Recusar o agendamento de {{ $paciente->nome }}?')">
                                                <i class="bi bi-x-lg" aria-hidden="true"></i> Recusar
                                            </button>
                                        </form>
                                    @endif
                                    @if ($agendamento->status === 'confirmado')
                                        <form method="POST" action="{{ route('agendamentos.update', $agendamento) }}" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="concluido">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-flag-fill" aria-hidden="true"></i> Concluir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @else
                        <article style="border-left:4px dashed #E5E7EB; background:#F2F6F5; border-radius:16px; padding:16px;" aria-label="Horario livre as {{ $slot['hora'] }}">
                            <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                                <span style="display:inline-flex; align-items:center; gap:8px; color:#6B7280; font-weight:700; font-size:0.9375rem; min-width:56px;">
                                    {{ $slot['hora'] }}
                                    <span style="width:10px; height:10px; border-radius:50%; border:2px solid #D1D5DB; flex-shrink:0;" aria-hidden="true"></span>
                                </span>
                                <div style="flex:1; min-width:200px;">
                                    <p style="font-size:0.9375rem; font-weight:600; color:#6B7280; margin:0;">Horario disponivel</p>
                                </div>
                                <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar disponibilidade
                                </a>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        @else
            <div style="border:2px dashed #E5E7EB; border-radius:16px; padding:40px 20px; text-align:center;">
                <i class="bi bi-calendar2-week" style="font-size:3rem; color:#E5E7EB; display:block; margin-bottom:12px;" aria-hidden="true"></i>
                <p style="color:#9CA3AF; font-weight:600; margin:0 0 4px;">Nenhum horario publicado neste dia</p>
                <p style="color:#9CA3AF; font-size:0.875rem; margin:0 0 16px;">Publique seus horarios disponiveis para receber pacientes</p>
                <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar disponibilidade
                </a>
            </div>
        @endif
    </section>

    {{-- =============================================
         AGENDAMENTOS DO DIA (com filtro por status)
         ============================================= --}}
    <section aria-label="Agendamentos do dia">
        <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:16px;">
            <div>
                <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">
                    <i class="bi bi-clipboard-data" aria-hidden="true"></i> Agendamentos do dia
                </h2>
                <p style="color:var(--color-text-secondary); margin:2px 0 0;">{{ $dataFormatada }}</p>
            </div>
        </div>

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

        <nav aria-label="Filtrar agendamentos por status" style="margin-bottom:16px;">
            <div style="display:flex; gap:8px; flex-wrap:wrap; border-bottom:2px solid #E5E7EB; padding-bottom:12px;">
                @foreach ($abas as $valor => $aba)
                    <a href="{{ route('agenda', array_merge(['data' => $data->toDateString()], $valor ? ['status' => $valor] : [])) }}"
                       aria-current="{{ $statusAtivo === ($valor ?: null) ? 'page' : 'false' }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:9999px; font-weight:700; font-size:0.875rem; text-decoration:none;
                              color:{{ $statusAtivo === ($valor ?: null) ? '#FFFFFF' : 'var(--color-text-secondary)' }};
                              background:{{ $statusAtivo === ($valor ?: null) ? '#009688' : 'transparent' }};">
                        <i class="{{ $aba['icone'] }}" aria-hidden="true"></i>
                        {{ $aba['label'] }}
                        <span class="badge {{ $statusAtivo === ($valor ?: null) ? 'badge-yellow' : 'badge-gray' }}">{{ $aba['contagem'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        @if ($agendamentosFiltrados->count() > 0)
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($agendamentosFiltrados as $agendamento)
                    <article class="card" style="padding:20px; border-radius:16px;" aria-label="Agendamento de {{ $agendamento->paciente->nome }}">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                            <div style="flex:1; min-width:240px;">
                                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:4px;">
                                    <h3 style="font-size:1.0625rem; font-weight:700; color:#111827; margin:0;">
                                        {{ $agendamento->paciente->nome }}
                                    </h3>
                                    <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                                </div>
                                <p style="color:var(--color-text-secondary); font-size:0.9375rem; margin:0;">
                                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                    {{ $agendamento->data->translatedFormat('l, d/m/Y') }}
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
                                            <i class="bi bi-flag-fill" aria-hidden="true"></i> Concluir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="card" style="padding:48px 24px; text-align:center;">
                <div style="font-size:3.5rem; margin-bottom:12px; color:#D1D5DB;" aria-hidden="true"><i class="bi bi-clipboard-data"></i></div>
                <p style="color:var(--color-text-secondary); font-size:1.0625rem; margin:0 0 16px;">
                    Nenhum agendamento {{ $statusAtivo ? 'com este status' : 'neste dia' }}.
                </p>
                @if (!$statusAtivo)
                    <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i> Divulgue seus horarios
                    </a>
                @endif
            </div>
        @endif
    </section>

</div>

@endsection
