@extends('layouts.app')

@section('titulo', 'Inicio')

@section('conteudo')

@php
    $fotosFallback = [
        'https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=300&q=80',
        'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=300&q=80',
        'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=300&q=80',
        'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=300&q=80',
    ];
    $dicasSaude = [
        'Alongue-se por 5 minutos antes de iniciar o trabalho. Cuide da sua postura!',
        'Beber água regularmente ajuda na recuperação muscular e previne cãibras.',
        'Uma caminhada de 30 minutos por dia reduz significativamente dores lombares.',
        'Ao ficar muito tempo sentado, levante-se a cada 40 minutos e movimente o corpo.',
        'Dormir bem é essencial para a recuperação muscular e a saúde da coluna.',
        'Fortaleça o core com exercícios leves para proteger sua lombar no dia a dia.',
    ];
    $dicaSaude = $dicasSaude[now()->dayOfYear % count($dicasSaude)];
    $dataHoje = \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd, DD [de] MMMM [de] YYYY');
@endphp

<div style="max-width:1024px; margin:0 auto; display:flex; flex-direction:column; gap:28px;">

    {{-- =============================================
         BLOCO 1 — HERO DE BOAS-VINDAS
         ============================================= --}}
    <section aria-label="Boas-vindas"
             style="background:linear-gradient(135deg,#1E3A3A 0%,#2D5050 100%); border-radius:20px; padding:40px 24px; color:#fff;">

        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <h1 style="color:#fff; font-size:1.5rem; font-weight:800; font-family:var(--font-heading); margin:0 0 4px;">
                    Olá, {{ $user->nome }}
                </h1>
                <p style="color:#99E6DC; font-size:0.875rem; margin:0 0 6px;">Como você está se sentindo hoje?</p>
                <p style="color:rgba(255,255,255,0.6); font-size:0.75rem; margin:0; text-transform:capitalize;">{{ $dataHoje }}</p>
            </div>
        </div>

        <a href="{{ route('clinicas.index') }}"
           style="display:flex; align-items:center; gap:12px; margin-top:24px; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.20); border-radius:16px; padding:14px 18px; color:rgba(255,255,255,0.85); text-decoration:none; transition:background 0.2s ease;"
           aria-label="Buscar fisioterapeuta por especialidade ou nome">
            <i class="bi bi-search" style="color:#fff; font-size:1.125rem;" aria-hidden="true"></i>
            <span style="font-size:0.9375rem;">Buscar fisioterapeuta por especialidade ou nome...</span>
        </a>
    </section>

    {{-- =============================================
         BLOCO 2 — CARDS DE AÇÕES RÁPIDAS
         ============================================= --}}
    <section aria-label="Acoes rapidas"
             style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">

        @php
            $acoes = [
                [
                    'href' => route('clinicas.index'),
                    'icone' => 'bi-search',
                    'cor' => '#009688',
                    'fundo' => '#E0F2F1',
                    'titulo' => 'Buscar Profissional',
                    'subtitulo' => 'Encontre o especialista ideal',
                    'badge' => null,
                ],
                [
                    'href' => route('dashboard'),
                    'icone' => 'bi-calendar2-check',
                    'cor' => '#D4AF37',
                    'fundo' => '#FFFBEB',
                    'titulo' => 'Meus Agendamentos',
                    'subtitulo' => 'Veja suas consultas marcadas',
                    'badge' => $agendamentosPendentes > 0 ? $agendamentosPendentes : null,
                ],
                [
                    'href' => route('clinicas.index'),
                    'icone' => 'bi-heart',
                    'cor' => '#EF4444',
                    'fundo' => '#FEF2F2',
                    'titulo' => 'Favoritos',
                    'subtitulo' => 'Profissionais salvos por você',
                    'badge' => null,
                ],
                [
                    'href' => route('clinicas.index'),
                    'icone' => 'bi-star',
                    'cor' => '#F59E0B',
                    'fundo' => '#FFFBEB',
                    'titulo' => 'Avaliações',
                    'subtitulo' => 'Histórico de feedback',
                    'badge' => null,
                ],
            ];
        @endphp

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
    </section>

    {{-- =============================================
         BLOCO 3 — PRÓXIMAS CONSULTAS
         ============================================= --}}
    <section aria-label="Proximas consultas">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:16px;">
            <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">Próximas consultas</h2>
            @if ($proximosAgendamentos->count() > 0)
                <a href="#todos-agendamentos" style="color:#009688; font-size:0.875rem; font-weight:600; text-decoration:none;">
                    Ver todos &rarr;
                </a>
            @endif
        </div>

        @if ($proximosAgendamentos->count() > 0)
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($proximosAgendamentos as $agendamento)
                    @php
                        $clinica = $agendamento->clinica;
                        $foto = $clinica->foto_capa ?: $fotosFallback[$clinica->id % count($fotosFallback)];
                        $especialidade = $clinica->especialidades->first();
                    @endphp
                    <article class="card" style="border-left:4px solid #009688; padding:16px; border-radius:16px;" aria-label="Agendamento com {{ $clinica->nome_fantasia }}">
                        <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                            <img src="{{ $foto }}" alt="Foto de {{ $clinica->nome_fantasia }}" loading="lazy"
                                 style="width:48px; height:48px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                            <div style="flex:1; min-width:220px;">
                                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                                    <h3 style="font-size:1rem; font-weight:700; color:#111827; margin:0;">
                                        <a href="{{ route('clinicas.show', $clinica) }}" style="color:inherit; text-decoration:none;">{{ $clinica->nome_fantasia }}</a>
                                    </h3>
                                    <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                                </div>
                                @if ($especialidade)
                                    <p style="color:#009688; font-size:0.8125rem; font-weight:600; margin:2px 0 8px;">{{ $especialidade->nome }}</p>
                                @endif
                                <div style="display:flex; gap:16px; flex-wrap:wrap; font-size:0.875rem; color:#6B7280;">
                                    <span><i class="bi bi-calendar3" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($agendamento->data)->translatedFormat('l, d/m') }}</span>
                                    <span><i class="bi bi-clock" aria-hidden="true"></i> {{ substr($agendamento->hora, 0, 5) }}</span>
                                    @if ($clinica->preco_sessao)
                                        <span><i class="bi bi-cash-coin" aria-hidden="true"></i> R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}</span>
                                    @endif
                                    <span><i class="bi bi-hospital" aria-hidden="true"></i> {{ $clinica->cidade }}</span>
                                </div>
                            </div>
                            @if ($agendamento->status === 'solicitado')
                                <form method="POST" action="{{ route('agendamentos.destroy', $agendamento) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="border:none; background:none; color:#EF4444; font-weight:600; cursor:pointer;"
                                            onclick="return confirm('Tem certeza que deseja cancelar?')">
                                        <i class="bi bi-x-lg" aria-hidden="true"></i> Cancelar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div style="border:2px dashed #E5E7EB; border-radius:16px; padding:40px 20px; text-align:center;">
                <i class="bi bi-calendar2-check" style="font-size:3rem; color:#E5E7EB; display:block; margin-bottom:12px;" aria-hidden="true"></i>
                <p style="color:#9CA3AF; font-weight:600; margin:0 0 4px;">Você ainda não tem consultas agendadas</p>
                <p style="color:#9CA3AF; font-size:0.875rem; margin:0 0 16px;">Nenhum problema! Encontre um fisioterapeuta agora.</p>
                <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
                    <i class="bi bi-search" aria-hidden="true"></i> Buscar fisioterapeuta
                </a>
            </div>
        @endif
    </section>

    {{-- =============================================
         BLOCO 4 — FISIOTERAPEUTAS RECOMENDADOS
         ============================================= --}}
    @if ($recomendados->count() > 0)
        <section aria-label="Profissionais recomendados">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:16px; margin-bottom:16px;">
                <div>
                    <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0;">Profissionais em destaque</h2>
                    <p style="color:#6B7280; font-size:0.875rem; margin:2px 0 0;">Mais bem avaliados da plataforma</p>
                </div>
                <a href="{{ route('clinicas.index') }}" style="color:#009688; font-size:0.875rem; font-weight:600; text-decoration:none; white-space:nowrap;">
                    Ver todos &rarr;
                </a>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
                @foreach ($recomendados as $clinica)
                    @php
                        $foto = $clinica->foto_capa ?: $fotosFallback[$clinica->id % count($fotosFallback)];
                        $especialidade = $clinica->especialidades->first();
                        $media = $clinica->mediaAvaliacoes();
                        $totalAvaliacoes = $clinica->totalAvaliacoes();
                    @endphp
                    <article class="card" style="padding:16px; border-radius:16px; display:flex; flex-direction:column; align-items:center; text-align:center;">
                        <img src="{{ $foto }}" alt="Foto de {{ $clinica->nome_fantasia }}" loading="lazy"
                             style="width:56px; height:56px; border-radius:50%; object-fit:cover; margin-bottom:10px; box-shadow:0 0 0 2px rgba(0,150,136,0.2);">
                        <h3 style="font-size:0.9375rem; font-weight:700; color:#111827; margin:0;">{{ $clinica->nome_fantasia }}</h3>
                        @if ($especialidade)
                            <p style="color:#009688; font-size:0.75rem; font-weight:600; margin:2px 0 8px;">{{ $especialidade->nome }}</p>
                        @endif
                        <p style="font-size:0.8125rem; color:#F59E0B; margin:0 0 4px;">
                            <i class="bi bi-star-fill" aria-hidden="true"></i> {{ number_format($media, 1, ',', '.') }}
                            <span style="color:#9CA3AF;">({{ $totalAvaliacoes }})</span>
                        </p>
                        @if ($clinica->preco_sessao)
                            <p style="font-size:0.875rem; color:#047857; font-weight:800; margin:0 0 12px;">R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}/sessão</p>
                        @else
                            <p style="font-size:0.875rem; color:#9CA3AF; margin:0 0 12px;">Valor sob consulta</p>
                        @endif
                        <a href="{{ route('clinicas.show', $clinica) }}" class="btn btn-primary btn-sm" style="width:100%; justify-content:center;">
                            <i class="bi bi-eye" aria-hidden="true"></i> Ver perfil
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- =============================================
         BLOCO 5 — DICA DE SAÚDE DO DIA
         ============================================= --}}
    <section aria-label="Dica de saude do dia"
             style="display:flex; align-items:center; gap:16px; background:linear-gradient(90deg,#E0F2F1 0%,#F0FDF9 100%); border:1px solid rgba(0,150,136,0.20); border-radius:16px; padding:20px;">
        <span style="display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:16px; background:#fff; flex-shrink:0;">
            <i class="bi bi-lightbulb" style="font-size:1.5rem; color:#009688;" aria-hidden="true"></i>
        </span>
        <div>
            <h2 style="font-size:1rem; font-weight:800; color:#009688; font-family:var(--font-heading); margin:0 0 4px;">Dica de hoje</h2>
            <p style="color:#374151; font-size:0.9375rem; margin:0;">{{ $dicaSaude }}</p>
        </div>
    </section>

    {{-- =============================================
         TODOS OS AGENDAMENTOS (histórico)
         ============================================= --}}
    <section id="todos-agendamentos" aria-label="Todos os agendamentos">
        <h2 style="font-size:1.25rem; font-weight:800; color:#111827; font-family:var(--font-heading); margin:0 0 16px;">
            <i class="bi bi-columns-gap" aria-hidden="true"></i> Meus agendamentos
        </h2>

        @if ($agendamentos->count() > 0)
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($agendamentos as $agendamento)
                    <article class="card" style="padding:20px; border-radius:16px;" aria-label="Agendamento com {{ $agendamento->clinica->nome_fantasia }}">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                            <div>
                                <h3 style="font-size:1.0625rem; font-weight:700; color:#111827; margin:0 0 4px;">
                                    <a href="{{ route('clinicas.show', $agendamento->clinica) }}" style="color:var(--color-primary); text-decoration:none;">
                                        {{ $agendamento->clinica->nome_fantasia }}
                                    </a>
                                </h3>
                                <p style="color:var(--color-text-secondary); font-size:0.9375rem; margin:0;">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}
                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                    {{ substr($agendamento->hora, 0, 5) }}
                                </p>
                                @if ($agendamento->observacao_clinica)
                                    <p style="color:var(--color-primary-dark); font-size:0.875rem; margin-top:8px; padding:8px 12px; background:var(--color-primary-light); border-radius:8px;">
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
            <div class="card" style="padding:48px 24px; text-align:center;">
                <i class="bi bi-calendar2-x" style="font-size:3rem; margin-bottom:12px; color:#D1D5DB; display:block;" aria-hidden="true"></i>
                <p style="color:var(--color-text-secondary); font-weight:600; margin:0 0 16px;">Você ainda não tem agendamentos.</p>
                <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
                    <i class="bi bi-search" aria-hidden="true"></i> Buscar clínicas de fisioterapia
                </a>
            </div>
        @endif
    </section>

</div>

<style>
    section[aria-label="Acoes rapidas"] a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: #D1D5DB;
    }
</style>

@endsection
