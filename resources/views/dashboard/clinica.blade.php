@extends('layouts.app')

@section('titulo', 'Painel da Clinica')

@section('conteudo')

@php
    $dataHoje = \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, DD [de] MMMM [de] YYYY');
    $especialidade = $clinica->especialidades->first();
    $fotoPerfil = $user->foto;
    $iniciais = mb_strtoupper(mb_substr($user->nome, 0, 2));
@endphp

<div style="max-width:1024px; margin:0 auto; display:flex; flex-direction:column; gap:28px;">

    {{-- =============================================
         BLOCO 6 — BANNER DE PERFIL INCOMPLETO
         ============================================= --}}
    @if ($perfilIncompleto)
        <section aria-label="Complete seu perfil"
                 style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; background:linear-gradient(90deg,#FFFBEB 0%,#FEF3C7 100%); border:1px solid rgba(212,175,55,0.40); border-radius:16px; padding:20px;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:2.25rem; color:#D4AF37;" aria-hidden="true"></i>
            <div style="flex:1; min-width:220px;">
                <h2 style="font-size:1rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0 0 2px;">Complete seu perfil</h2>
                <p style="color:#6B7280; font-size:0.875rem; margin:0;">Perfis completos recebem 3&times; mais agendamentos</p>
            </div>
            <a href="{{ route('clinica.perfil.edit') }}" class="btn" style="background:#D4AF37; border-color:#D4AF37; color:#fff; font-weight:700;">
                <i class="bi bi-pencil-fill" aria-hidden="true"></i> Completar agora
            </a>
        </section>
    @endif

    {{-- =============================================
         BLOCO 1 — HERO DO PROFISSIONAL
         ============================================= --}}
    <section aria-label="Boas-vindas ao profissional"
             style="background:linear-gradient(135deg,#1E3A3A 0%,#00796B 100%); border-radius:20px; padding:40px 24px; color:#fff;">

        <div style="display:flex; justify-content:space-between; align-items:center; gap:24px; flex-wrap:wrap;">

            <div style="flex:1; min-width:260px;">
                <h1 style="color:#fff; font-size:1.5rem; font-weight:800; font-family:var(--font-heading); margin:0 0 4px;">
                    Olá, Dr(a). {{ $user->nome }}
                </h1>
                @if ($especialidade)
                    <p style="color:#99E6DC; font-size:0.875rem; font-weight:600; margin:0 0 6px;">{{ $especialidade->nome }}</p>
                @endif
                <p style="color:rgba(255,255,255,0.6); font-size:0.75rem; margin:0 0 12px; text-transform:capitalize;">{{ $dataHoje }}</p>

                @if ($user->crefito)
                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.20); border-radius:9999px; padding:4px 14px; color:#fff; font-size:0.75rem; font-weight:700;">
                        <i class="bi bi-patch-check-fill" aria-hidden="true"></i> {{ $user->crefito }}
                    </span>
                @endif
            </div>

            <div style="flex-shrink:0;">
                @if ($fotoPerfil)
                    <img src="{{ $fotoPerfil }}" alt="Foto de {{ $user->nome }}" loading="lazy"
                         style="width:72px; height:72px; border-radius:50%; object-fit:cover; box-shadow:0 0 0 4px rgba(255,255,255,0.30);">
                @else
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:72px; height:72px; border-radius:50%; background:rgba(255,255,255,0.15); border:4px solid rgba(255,255,255,0.30); color:#fff; font-weight:800; font-size:1.5rem; font-family:var(--font-heading);"
                          aria-hidden="true">{{ $iniciais }}</span>
                @endif
            </div>

        </div>
    </section>

    {{-- =============================================
         BLOCO 2 — MÉTRICAS RÁPIDAS (KPIs)
         ============================================= --}}
    <section aria-label="Metricas rapidas"
             style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">

        @php
            $kpis = [
                [
                    'numero' => $sessoesHoje,
                    'label' => 'Sessões hoje',
                    'variacao' => 'Agenda do dia',
                    'icone' => 'bi-calendar2-check',
                    'cor' => '#009688',
                    'fundo' => '#E0F2F1',
                    'link' => route('agenda', ['data' => today()->toDateString()]),
                    'pulso' => false,
                ],
                [
                    'numero' => $pacientesAtivos,
                    'label' => 'Pacientes ativos',
                    'variacao' => 'Únicos no período',
                    'icone' => 'bi-people',
                    'cor' => '#D4AF37',
                    'fundo' => '#FFFBEB',
                    'link' => route('agenda'),
                    'pulso' => false,
                ],
                [
                    'numero' => number_format($avaliacaoMedia, 1, ',', '.'),
                    'label' => 'Avaliação média',
                    'variacao' => '(' . $totalAvaliacoes . ' avaliações)',
                    'icone' => 'bi-star-fill',
                    'cor' => '#F59E0B',
                    'fundo' => '#FFFBEB',
                    'link' => '#avaliacoes-recentes',
                    'pulso' => false,
                ],
                [
                    'numero' => $contagem['solicitado'],
                    'label' => 'Aguardando confirmação',
                    'variacao' => 'Pendências',
                    'icone' => 'bi-bell',
                    'cor' => '#EF4444',
                    'fundo' => '#FEF2F2',
                    'link' => route('agenda', ['status' => 'solicitado']),
                    'pulso' => $contagem['solicitado'] > 0,
                ],
            ];
        @endphp

        @foreach ($kpis as $kpi)
            <a href="{{ $kpi['link'] }}"
               style="display:flex; flex-direction:column; justify-content:center; min-height:140px; padding:20px; background:#fff; border:1px solid #E3E9E8; border-radius:16px; box-shadow:0 1px 2px rgba(16,42,42,0.05), 0 4px 14px rgba(16,42,42,0.06); text-decoration:none; position:relative; transition:transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:12px; background:{{ $kpi['fundo'] }}; position:absolute; top:16px; right:16px;">
                    <i class="{{ $kpi['icone'] }}" style="font-size:1.25rem; color:{{ $kpi['cor'] }}; position:relative;" aria-hidden="true">
                        @if ($kpi['pulso'])
                            <span style="position:absolute; top:-2px; right:-2px; width:8px; height:8px; border-radius:50%; background:#EF4444; animation:pulse-kpi 1.6s ease-out infinite;"></span>
                        @endif
                    </i>
                </span>
                <p style="font-size:1.75rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0 0 2px; padding-right:48px;">{{ $kpi['numero'] }}</p>
                <p style="color:#6B7280; font-size:0.75rem; font-weight:600; margin:0 0 4px; text-transform:uppercase; letter-spacing:0.04em;">{{ $kpi['label'] }}</p>
                <p style="color:#059669; font-size:0.75rem; margin:0;">{{ $kpi['variacao'] }}</p>
            </a>
        @endforeach
    </section>

    {{-- =============================================
         BLOCO 3 — AGENDA DE HOJE (PRÉVIA)
         ============================================= --}}
    <section id="agenda-hoje" aria-label="Agenda de hoje">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:16px;">
            <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">Agenda de hoje</h2>
            <a href="{{ route('agenda', ['data' => today()->toDateString()]) }}"
               style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-size:0.875rem; font-weight:700; text-decoration:none;">
                Ver agenda completa <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        @if ($agendaHoje->count() > 0 || $slotsHoje->count() > 0)
            <div class="card" style="padding:0; border-radius:16px; overflow:hidden;">
                @foreach ($slotsHoje->take(3) as $slot)
                    @php $agendamento = $slot['agendamento']; @endphp
                    @if ($agendamento)
                        @php $paciente = $agendamento->paciente; @endphp
                        <div style="display:flex; align-items:center; gap:14px; padding:16px 20px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}" aria-label="Agendamento de {{ $paciente->nome }} as {{ $slot['hora'] }}">
                            <span style="display:inline-flex; align-items:center; gap:8px; color:#111827; font-weight:700; font-size:0.9375rem; min-width:56px;">
                                {{ $slot['hora'] }}
                                <span style="width:10px; height:10px; border-radius:50%; background:#22C55E; flex-shrink:0;" aria-hidden="true"></span>
                            </span>
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#E0F2F1; color:#009688; font-weight:800; font-size:0.8125rem; flex-shrink:0;" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($paciente->nome, 0, 2)) }}
                            </span>
                            <div style="flex:1; min-width:180px;">
                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                    <strong style="color:#111827; font-size:0.9375rem;">{{ $paciente->nome }}</strong>
                                    <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                                </div>
                                @if ($paciente->condicao)
                                    <p style="color:#6B7280; font-size:0.8125rem; margin:2px 0 0;">
                                        <i class="bi bi-heart-pulse" aria-hidden="true"></i> {{ $paciente->condicao }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div style="display:flex; align-items:center; gap:14px; padding:16px 20px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}" aria-label="Horario livre as {{ $slot['hora'] }}">
                            <span style="display:inline-flex; align-items:center; gap:8px; color:#6B7280; font-weight:700; font-size:0.9375rem; min-width:56px;">
                                {{ $slot['hora'] }}
                                <span style="width:10px; height:10px; border-radius:50%; border:2px solid #D1D5DB; flex-shrink:0;" aria-hidden="true"></span>
                            </span>
                            <p style="font-size:0.9375rem; font-weight:600; color:#6B7280; margin:0;">Horário disponível</p>
                        </div>
                    @endif
                @endforeach

                @if ($slotsHoje->count() > 3 || $agendaHoje->count() > $slotsHoje->count())
                    <a href="{{ route('agenda', ['data' => today()->toDateString()]) }}"
                       style="display:block; text-align:center; padding:14px 20px; background:#F2F6F5; color:#009688; font-weight:700; font-size:0.875rem; text-decoration:none; border-top:1px solid #F3F4F6;">
                        Ver todos os horários do dia <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
        @else
            <div style="border:2px dashed #E5E7EB; border-radius:16px; padding:32px 20px; text-align:center;">
                <i class="bi bi-calendar2-week" style="font-size:2.5rem; color:#E5E7EB; display:block; margin-bottom:12px;" aria-hidden="true"></i>
                <p style="color:#9CA3AF; font-weight:600; margin:0 0 4px;">Sua agenda de hoje está livre</p>
                <p style="color:#9CA3AF; font-size:0.875rem; margin:0 0 16px;">Publique seus horários disponíveis para receber pacientes</p>
                <a href="{{ route('clinica.perfil.edit') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar disponibilidade
                </a>
            </div>
        @endif
    </section>

    {{-- =============================================
         BLOCO 4 — AÇÕES RÁPIDAS DO PROFISSIONAL
         ============================================= --}}
    <section aria-label="Acoes rapidas do profissional">
        <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0 0 16px;">Gerenciar</h2>

        @php
            $acoes = [
                [
                    'href' => route('clinica.perfil.edit'),
                    'icone' => 'bi-plus-circle',
                    'cor' => '#009688',
                    'fundo' => '#E0F2F1',
                    'titulo' => 'Publicar Horários',
                    'subtitulo' => 'Informe quando você está disponível',
                    'badge' => null,
                ],
                [
                    'href' => route('agenda'),
                    'icone' => 'bi-calendar3',
                    'cor' => '#D4AF37',
                    'fundo' => '#FFFBEB',
                    'titulo' => 'Minha Agenda',
                    'subtitulo' => 'Visualize e organize atendimentos',
                    'badge' => null,
                ],
                [
                    'href' => route('chat.index'),
                    'icone' => 'bi-chat-dots',
                    'cor' => '#6366F1',
                    'fundo' => '#EEF2FF',
                    'titulo' => 'Mensagens',
                    'subtitulo' => 'Converse com seus pacientes',
                    'badge' => $naoLidas > 0 ? $naoLidas : null,
                ],
                [
                    'href' => route('clinica.perfil.edit'),
                    'icone' => 'bi-person',
                    'cor' => '#EC4899',
                    'fundo' => '#FDF2F8',
                    'titulo' => 'Meu Perfil',
                    'subtitulo' => 'Edite suas informações profissionais',
                    'badge' => null,
                ],
            ];
        @endphp

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            @foreach ($acoes as $acao)
                <a href="{{ $acao['href'] }}"
                   style="display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; min-height:150px; padding:20px; background:#fff; border:1px solid #E3E9E8; border-radius:16px; box-shadow:0 1px 2px rgba(16,42,42,0.05), 0 4px 14px rgba(16,42,42,0.06); text-decoration:none; position:relative; transition:transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;">
                    @if ($acao['badge'])
                        <span style="position:absolute; top:12px; right:12px; background:#009688; color:#fff; font-size:0.75rem; font-weight:800; min-width:22px; height:22px; border-radius:9999px; display:inline-flex; align-items:center; justify-content:center; padding:0 6px;">
                            {{ $acao['badge'] }}
                        </span>
                    @endif
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:16px; background:{{ $acao['fundo'] }}; margin-bottom:12px;">
                        <i class="{{ $acao['icone'] }}" style="font-size:1.5rem; color:{{ $acao['cor'] }};" aria-hidden="true"></i>
                    </span>
                    <strong style="color:#111827; font-size:0.9375rem; font-weight:700; margin-bottom:4px;">{{ $acao['titulo'] }}</strong>
                    <span style="color:#6B7280; font-size:0.8125rem;">{{ $acao['subtitulo'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- =============================================
         BLOCO 5 — ÚLTIMAS AVALIAÇÕES RECEBIDAS
         ============================================= --}}
    <section id="avaliacoes-recentes" aria-label="Avaliacoes recentes">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:16px;">
            <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">Avaliações recentes</h2>
            <a href="#todos-agendamentos" style="color:#009688; font-size:0.875rem; font-weight:600; text-decoration:none;">
                Ver todas &rarr;
            </a>
        </div>

        @if ($ultimasAvaliacoes->count() > 0)
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($ultimasAvaliacoes as $avaliacao)
                    <article style="background:#F2F6F5; border:1px solid #F3F4F6; border-radius:16px; padding:16px;" aria-label="Avaliacao de {{ $avaliacao->paciente->nome }}">
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:8px;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#E0F2F1; color:#009688; font-weight:800; font-size:0.8125rem;" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($avaliacao->paciente->nome, 0, 2)) }}
                            </span>
                            <strong style="font-size:0.875rem; color:#111827;">{{ $avaliacao->paciente->nome }}</strong>
                            <span style="color:#9CA3AF; font-size:0.75rem;">
                                <i class="bi bi-patch-check-fill" style="color:#009688;" aria-hidden="true"></i> Paciente verificado
                                &middot; {{ $avaliacao->created_at->translatedFormat('d/m/Y') }}
                            </span>
                            <span style="margin-left:auto; color:#F59E0B; font-size:0.8125rem;" aria-label="{{ $avaliacao->nota }} de 5 estrelas">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                                @endfor
                            </span>
                        </div>
                        @if ($avaliacao->comentario)
                            <p style="color:#4B5563; font-size:0.875rem; font-style:italic; margin:0;">&ldquo;{{ $avaliacao->comentario }}&rdquo;</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div style="border:2px dashed #E5E7EB; border-radius:16px; padding:40px 20px; text-align:center;">
                <i class="bi bi-star" style="font-size:3rem; color:#E5E7EB; display:block; margin-bottom:12px;" aria-hidden="true"></i>
                <p style="color:#9CA3AF; font-weight:600; margin:0;">Você ainda não recebeu avaliações</p>
            </div>
        @endif
    </section>

</div>

<style>
    section[aria-label="Metricas rapidas"] a:hover,
    section[aria-label="Acoes rapidas do profissional"] a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: #D1D5DB;
    }
    @keyframes pulse-kpi {
        0% { opacity: 1; transform: scale(0.6); }
        100% { opacity: 0; transform: scale(1.4); }
    }
</style>

@endsection
