@extends('layouts.app')

@section('titulo', $clinica->nome_fantasia)

@section('conteudo')
@php
    $especialidadePrincipal = $clinica->especialidades->first();
    $especialidadesBadges = $clinica->especialidades->count() > 1
        ? $clinica->especialidades->skip(1)
        : $clinica->especialidades;

    $fotosClinicaCapa = [
        'https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=300&q=80',
        'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=300&q=80',
        'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=300&q=80',
        'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=300&q=80',
    ];
    $fotoClinicaCapa = $clinica->foto_capa ?: $fotosClinicaCapa[$clinica->id % count($fotosClinicaCapa)];

    $fotosEspacoFallback = [
        ['src' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=500&q=80', 'legenda' => 'Rampa de acesso'],
        ['src' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=500&q=80', 'legenda' => 'Corredor amplo com corrimao'],
        ['src' => 'https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=500&q=80', 'legenda' => 'Sala de fisioterapia equipada'],
    ];

    // Monta a agenda com datas reais a partir dos horarios cadastrados
    $mapaDiaSemana = [0 => 'domingo', 1 => 'segunda', 2 => 'terca', 3 => 'quarta', 4 => 'quinta', 5 => 'sexta', 6 => 'sabado'];
    $abrevDia = [0 => 'DOM', 1 => 'SEG', 2 => 'TER', 3 => 'QUA', 4 => 'QUI', 5 => 'SEX', 6 => 'SAB'];
    $horariosPorDia = $clinica->horarios->where('ativo', true)->keyBy('dia_semana');

    $agendamentosOcupados = $clinica->agendamentos
        ->whereNotIn('status', ['cancelado', 'recusado'])
        ->map(fn ($a) => \Carbon\Carbon::parse($a->data)->format('Y-m-d') . '|' . substr($a->hora, 0, 5))
        ->all();

    $hoje = \Carbon\Carbon::today();
    $agendaDias = collect();

    for ($i = 0; $i < 21 && $agendaDias->count() < 5; $i++) {
        $data = $hoje->copy()->addDays($i);
        $nomeDia = $mapaDiaSemana[$data->dayOfWeek];

        if (!$horariosPorDia->has($nomeDia)) {
            continue;
        }

        $horario = $horariosPorDia[$nomeDia];
        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
        $fim = \Carbon\Carbon::parse($horario->hora_fim);
        $slots = [];
        $cursor = $inicio->copy();
        while ($cursor->lt($fim)) {
            $chave = $data->format('Y-m-d') . '|' . $cursor->format('H:i');
            $slots[] = [
                'hora' => $cursor->format('H:i'),
                'ocupado' => in_array($chave, $agendamentosOcupados),
            ];
            $cursor->addMinutes(90);
        }

        $agendaDias->push([
            'data' => $data->format('Y-m-d'),
            'label' => $abrevDia[$data->dayOfWeek] . ', DIA ' . $data->format('d'),
            'slots' => $slots,
        ]);
    }

    $podeAvaliar = auth()->check()
        && auth()->user()->isPaciente()
        && $clinica->agendamentos->contains(function ($a) {
            return $a->paciente_id === auth()->id() && $a->status === 'concluido';
        });

    $jaAvaliou = auth()->check()
        && $clinica->avaliacoes->contains('paciente_id', auth()->id());
@endphp

<a href="{{ route('clinicas.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar para busca
</a>

<div class="perfil-clinica-grid">
    {{-- Conteudo Principal --}}
    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- Cabecalho --}}
        <section class="card" style="padding:24px;" aria-label="Informacoes principais da clinica">
            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div class="perfil-clinica-avatar">
                    <img src="{{ $fotoClinicaCapa }}" alt="Foto da clinica {{ $clinica->nome_fantasia }}" loading="lazy">
                </div>

                <div style="flex:1; min-width:220px;">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <h1 style="font-size:1.5rem; font-weight:800; color:var(--color-text); margin:0;">{{ $clinica->nome_fantasia }}</h1>
                        <i class="bi bi-patch-check-fill" style="color:#009688; font-size:1.125rem;" aria-hidden="true" title="CNPJ verificado"></i>
                    </div>

                    @if ($especialidadePrincipal)
                        <p style="color:#009688; font-weight:600; font-size:0.9375rem; margin:2px 0 0;">{{ $especialidadePrincipal->nome }}</p>
                    @endif

                    <p style="color:var(--color-text-secondary); font-size:0.875rem; margin:2px 0 10px;">
                        <i class="bi bi-geo-alt-fill" aria-hidden="true"></i> {{ $clinica->cidade }} - {{ $clinica->estado }}
                    </p>

                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                        <div class="star-rating" aria-label="Nota {{ $clinica->mediaAvaliacoes() }} de 5 estrelas">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($clinica->mediaAvaliacoes()) ? 'filled' : '' }}" aria-hidden="true"><i class="bi bi-star-fill"></i></span>
                            @endfor
                        </div>
                        <span style="font-weight:700; color:var(--color-text);">{{ number_format($clinica->mediaAvaliacoes(), 1) }}</span>
                        <span style="color:var(--color-text-secondary); font-size:0.875rem;">({{ $clinica->totalAvaliacoes() }} avaliacoes)</span>
                    </div>

                    @if ($especialidadesBadges->count() > 0)
                        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px;">
                            @foreach ($especialidadesBadges->take(4) as $esp)
                                <span class="badge badge-blue">{{ $esp->nome }}</span>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ auth()->check() && auth()->user()->isPaciente() ? route('chat.iniciar', $clinica) : route('login') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-chat-dots" aria-hidden="true"></i> Enviar mensagem
                    </a>
                </div>
            </div>
        </section>

        {{-- Abas --}}
        <section class="card" style="padding:24px;" aria-label="Detalhes da clinica">
            <div class="perfil-tabs" role="tablist" aria-label="Secoes do perfil da clinica">
                <button type="button" class="perfil-tab ativo" role="tab" aria-selected="true" aria-controls="painel-sobre"
                        onclick="window.DailyCare.perfilClinica.mudarAba('sobre', this)">Sobre</button>
                <button type="button" class="perfil-tab" role="tab" aria-selected="false" aria-controls="painel-acessibilidade"
                        onclick="window.DailyCare.perfilClinica.mudarAba('acessibilidade', this)">Acessibilidade</button>
                <button type="button" class="perfil-tab" role="tab" aria-selected="false" aria-controls="painel-avaliacoes"
                        onclick="window.DailyCare.perfilClinica.mudarAba('avaliacoes', this)">Avaliacoes</button>
            </div>

            {{-- Aba: Sobre --}}
            <div id="painel-sobre" class="perfil-tab-painel ativo" role="tabpanel">
                @if ($clinica->descricao)
                    <h3 style="font-weight:700; color:var(--color-text); margin-bottom:8px;">Sobre</h3>
                    <p style="color:var(--color-text); line-height:1.7; margin-bottom:20px;">{{ $clinica->descricao }}</p>
                @endif

                <h3 style="font-weight:700; color:var(--color-text); margin-bottom:12px;">Contato</h3>
                <div style="display:flex; flex-direction:column; gap:10px; font-size:0.9375rem;">
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="bi bi-telephone-fill" style="color:var(--color-text-secondary); margin-top:2px;" aria-hidden="true"></i>
                        <span style="color:var(--color-text);">{{ $clinica->telefone ?: 'Nao informado' }}</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="bi bi-envelope-fill" style="color:var(--color-text-secondary); margin-top:2px;" aria-hidden="true"></i>
                        <span style="color:var(--color-text);">{{ $clinica->email_contato ?: 'Nao informado' }}</span>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="bi bi-geo-alt-fill" style="color:var(--color-text-secondary); margin-top:2px;" aria-hidden="true"></i>
                        <span style="color:var(--color-text);">{{ $clinica->enderecoCompleto() }}</span>
                    </div>
                </div>
            </div>

            {{-- Aba: Acessibilidade --}}
            <div id="painel-acessibilidade" class="perfil-tab-painel" role="tabpanel">
                <h3 style="font-weight:700; color:var(--color-text); margin-bottom:12px;">Recursos de acessibilidade</h3>
                @if ($clinica->servicosAcessibilidade->count() > 0)
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:10px; margin-bottom:24px;">
                        @foreach ($clinica->servicosAcessibilidade as $servico)
                            <div style="display:flex; align-items:center; gap:10px; padding:12px 14px; background:#F0FDF9; border:1px solid #A7F3D0; border-radius:10px;">
                                <i class="bi bi-check-circle-fill" style="color:#047857;" aria-hidden="true"></i>
                                <span style="font-weight:600; color:#065F46; font-size:0.9375rem;">{{ $servico->nome }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color:var(--color-text-secondary); padding:20px; text-align:center; margin-bottom:24px;">Nenhum recurso de acessibilidade cadastrado.</p>
                @endif

                <h3 style="font-weight:700; color:var(--color-text); margin-bottom:12px;">Fotos do espaco</h3>
                @php
                    $fotosParaExibir = $clinica->fotos->count() > 0
                        ? $clinica->fotos->map(fn ($f) => ['src' => $f->caminho, 'legenda' => $f->legenda ?: 'Foto da clinica'])
                        : collect($fotosEspacoFallback);
                @endphp
                <div class="fotos-carrossel">
                    <button type="button" class="fotos-carrossel-seta fotos-carrossel-seta-esquerda"
                            onclick="window.DailyCare.perfilClinica.moverCarrossel(this, -1)" aria-label="Foto anterior">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </button>

                    <div class="fotos-carrossel-viewport">
                        <div class="fotos-carrossel-trilho">
                            @foreach ($fotosParaExibir as $foto)
                                <div class="fotos-carrossel-item">
                                    <img src="{{ $foto['src'] }}" alt="{{ $foto['legenda'] }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" class="fotos-carrossel-seta fotos-carrossel-seta-direita"
                            onclick="window.DailyCare.perfilClinica.moverCarrossel(this, 1)" aria-label="Proxima foto">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
                <p style="color:var(--color-text-secondary); font-size:0.875rem; margin-top:8px;">
                    Fotos ilustrativas. A estrutura real pode variar - fale com a clinica para confirmar detalhes especificos.
                </p>
            </div>

            {{-- Aba: Avaliacoes --}}
            <div id="painel-avaliacoes" class="perfil-tab-painel" role="tabpanel">
                @if ($clinica->avaliacoes->count() > 0)
                    <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:24px;">
                        @foreach ($clinica->avaliacoes as $avaliacao)
                            <div style="padding-bottom:20px; border-bottom:1px solid #F3F4F6;">
                                <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                                    <div style="width:40px; height:40px; background:#E0F2F1; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#009688;">
                                        {{ substr($avaliacao->paciente->nome, 0, 1) }}
                                    </div>
                                    <div>
                                        <span style="font-weight:600; color:var(--color-text);">{{ $avaliacao->paciente->nome }}</span>
                                        <div class="star-rating" aria-label="Nota {{ $avaliacao->nota }} de 5" style="margin-top:2px;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $avaliacao->nota ? 'filled' : '' }}" aria-hidden="true" style="font-size:0.875rem;"><i class="bi bi-star-fill"></i></span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @if ($avaliacao->comentario)
                                    <p style="color:var(--color-text); line-height:1.6;">{{ $avaliacao->comentario }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color:var(--color-text-secondary); padding:24px; text-align:center; border:1px dashed #D1D5DB; border-radius:12px; margin-bottom:24px;">
                        Ainda nao ha avaliacoes com comentarios.
                    </p>
                @endif

                @auth
                    @if (Auth::user()->isPaciente())
                        <div style="padding-top:20px; border-top:2px solid #E5E7EB;">
                            @if ($jaAvaliou)
                                <p style="color:var(--color-text-secondary); padding:16px; background:#F9FAFB; border-radius:10px;">
                                    <i class="bi bi-check-circle-fill" style="color:#009688;" aria-hidden="true"></i>
                                    Voce ja avaliou esta clinica. Obrigado pelo feedback!
                                </p>
                            @elseif ($podeAvaliar)
                                <h3 style="font-weight:700; color:var(--color-text); margin-bottom:16px;">Deixe sua avaliacao</h3>
                                <form method="POST" action="{{ route('avaliacoes.store') }}" aria-label="Formulario de avaliacao">
                                    @csrf
                                    <input type="hidden" name="clinica_id" value="{{ $clinica->id }}">

                                    <div class="form-group" style="margin-bottom:16px;">
                                        <label class="form-label">Nota</label>
                                        <fieldset style="border:none; padding:0;">
                                            <legend class="sr-only">Selecione uma nota de 1 a 5</legend>
                                            <div class="star-rating-input" role="radiogroup" aria-label="Nota de 1 a 5 estrelas">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <label title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}">
                                                        <input type="radio" name="nota" value="{{ $i }}" required>
                                                        <i class="bi bi-star-fill" aria-hidden="true"></i>
                                                        <span class="sr-only">{{ $i }} estrela{{ $i > 1 ? 's' : '' }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="form-group" style="margin-bottom:20px;">
                                        <label for="comentario" class="form-label">Comentario (opcional)</label>
                                        <textarea id="comentario" name="comentario" rows="3" class="form-textarea"
                                                  placeholder="Como foi sua experiencia?"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Enviar avaliacao</button>
                                </form>
                            @else
                                <p style="color:var(--color-text-secondary); padding:16px; background:#F9FAFB; border-radius:10px;">
                                    <i class="bi bi-info-circle" aria-hidden="true"></i>
                                    Voce podera avaliar esta clinica apos ter um atendimento concluido por aqui.
                                </p>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </section>
    </div>

    {{-- Sidebar: Agendamento --}}
    <aside style="position:sticky; top:88px;">
        @auth
            @if (Auth::user()->isPaciente())
                <div class="agenda-card">
                    <div class="agenda-card-header">
                        <h2>Agendar atendimento</h2>
                        <p>Selecione um horario disponivel</p>
                    </div>

                    <div class="agenda-card-corpo">
                        @if ($clinica->preco_sessao)
                            <div class="agenda-preco-linha">
                                <span>Valor da sessao</span>
                                <strong>R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}</strong>
                            </div>
                        @endif

                        @if ($agendaDias->count() > 0)
                            @foreach ($agendaDias as $dia)
                                <div class="agenda-dia-bloco">
                                    <span class="agenda-dia-label">{{ $dia['label'] }}</span>
                                    <div class="agenda-slots-wrap">
                                        @foreach ($dia['slots'] as $slot)
                                            <button type="button"
                                                    class="horario-slot {{ $slot['ocupado'] ? 'ocupado' : '' }}"
                                                    {{ $slot['ocupado'] ? 'disabled' : '' }}
                                                    data-data="{{ $dia['data'] }}"
                                                    data-hora="{{ $slot['hora'] }}"
                                                    data-label="{{ $dia['label'] }}"
                                                    onclick="window.DailyCare.perfilClinica.abrirModalAgendamento(this)">
                                                {{ $slot['hora'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p style="color:var(--color-text-secondary); text-align:center; padding:12px 0;">
                                Esta clinica ainda nao cadastrou horarios disponiveis.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Modal de confirmacao de agendamento --}}
                <div id="modal-agendamento-backdrop" class="agenda-modal-backdrop">
                    <div class="agenda-modal" role="dialog" aria-modal="true" aria-labelledby="agenda-modal-titulo">
                        <div class="agenda-modal-header">
                            <h2 id="agenda-modal-titulo">Confirmar agendamento</h2>
                            <button type="button" onclick="window.DailyCare.perfilClinica.fecharModalAgendamento()" aria-label="Fechar">
                                <i class="bi bi-x-lg" aria-hidden="true"></i>
                            </button>
                        </div>

                        <div class="agenda-modal-corpo">
                            <div class="agenda-modal-clinica">
                                <img src="{{ $fotoClinicaCapa }}" alt="" loading="lazy">
                                <div>
                                    <strong>{{ $clinica->nome_fantasia }}</strong>
                                    @if ($especialidadePrincipal)
                                        <span>{{ $especialidadePrincipal->nome }}</span>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('agendamentos.store') }}" id="form-agendamento">
                                @csrf
                                <input type="hidden" name="clinica_id" value="{{ $clinica->id }}">
                                <input type="hidden" name="data" id="agendamento-data" value="">
                                <input type="hidden" name="hora" id="agendamento-hora" value="">

                                <div class="agenda-modal-info-grid {{ $clinica->preco_sessao ? '' : 'sem-preco' }}">
                                    <div>
                                        <span>Dia</span>
                                        <strong id="agenda-modal-dia">-</strong>
                                    </div>
                                    <div>
                                        <span>Horario</span>
                                        <strong id="agenda-modal-hora">-</strong>
                                    </div>
                                    @if ($clinica->preco_sessao)
                                        <div>
                                            <span>Valor</span>
                                            <strong>R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group" style="margin:16px 0 0;">
                                    <label for="observacao_paciente" class="form-label">Observacoes (opcional)</label>
                                    <textarea id="observacao_paciente" name="observacao_paciente" rows="2" class="form-textarea"
                                              placeholder="Descreva sua condicao ou necessidade..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:16px;">
                                    <i class="bi bi-calendar-check" aria-hidden="true"></i> Solicitar agendamento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="agenda-card">
                <div class="agenda-card-header">
                    <h2>Agendar atendimento</h2>
                    <p>Entre para solicitar um horario</p>
                </div>
                <div style="padding:24px; text-align:center;">
                    <p style="color:var(--color-text-secondary); margin-bottom:14px;">Faca login como paciente para agendar com esta clinica.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%; justify-content:center;">Entrar</a>
                </div>
            </div>
        @endauth
    </aside>
</div>

<script>
    window.DailyCare = window.DailyCare || {};
    window.DailyCare.perfilClinica = {
        mudarAba(nome, botao) {
            document.querySelectorAll('.perfil-tab').forEach(function (tab) {
                tab.classList.remove('ativo');
                tab.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.perfil-tab-painel').forEach(function (painel) {
                painel.classList.remove('ativo');
            });
            botao.classList.add('ativo');
            botao.setAttribute('aria-selected', 'true');
            document.getElementById('painel-' + nome).classList.add('ativo');
        },
        abrirModalAgendamento(botao) {
            if (botao.disabled) return;

            document.querySelectorAll('.horario-slot').forEach(function (b) {
                b.classList.remove('selecionado');
            });
            botao.classList.add('selecionado');

            document.getElementById('agendamento-data').value = botao.dataset.data;
            document.getElementById('agendamento-hora').value = botao.dataset.hora;
            document.getElementById('agenda-modal-hora').textContent = botao.dataset.hora;
            document.getElementById('agenda-modal-dia').textContent = botao.dataset.label;

            document.getElementById('modal-agendamento-backdrop').classList.add('aberto');
            document.body.style.overflow = 'hidden';
        },
        fecharModalAgendamento() {
            document.getElementById('modal-agendamento-backdrop').classList.remove('aberto');
            document.body.style.overflow = '';
        },
        moverCarrossel(botao, direcao) {
            const carrossel = botao.closest('.fotos-carrossel');
            const viewport = carrossel.querySelector('.fotos-carrossel-viewport');
            viewport.scrollBy({ left: viewport.clientWidth * direcao, behavior: 'smooth' });
        }
    };

    document.getElementById('modal-agendamento-backdrop')?.addEventListener('click', function (evento) {
        if (evento.target === this) {
            window.DailyCare.perfilClinica.fecharModalAgendamento();
        }
    });
</script>

@push('head')
<style>
    .perfil-clinica-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .perfil-clinica-grid {
            grid-template-columns: 1fr;
        }

        .perfil-clinica-grid aside {
            position: static !important;
        }
    }
</style>
@endpush
@endsection
