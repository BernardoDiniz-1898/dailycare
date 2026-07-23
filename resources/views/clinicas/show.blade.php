@extends('layouts.app')

@section('titulo', $clinica->nome_fantasia)

@section('conteudo')
<a href="{{ route('clinicas.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <span aria-hidden="true">&#x2190;</span> Voltar para busca
</a>

<div style="display:grid; grid-template-columns:1fr 380px; gap:32px; align-items:start;">
    {{-- Conteudo Principal --}}
    <div style="display:flex; flex-direction:column; gap:24px;">
        {{-- Cabecalho --}}
        <section class="card" style="padding:32px;" aria-label="Informacoes principais da clinica">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; margin-bottom:16px;">
                <div>
                    <h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:4px;">{{ $clinica->nome_fantasia }}</h1>
                    <p style="color:#6B7280;">{{ $clinica->razao_social }}</p>
                </div>
                <div style="text-align:right;">
                    <div style="display:flex; align-items:center; gap:6px;" aria-label="Nota {{ $clinica->mediaAvaliacoes() }} de 5 estrelas">
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($clinica->mediaAvaliacoes()) ? 'filled' : '' }}" aria-hidden="true">&#x2605;</span>
                            @endfor
                        </div>
                        <span style="font-weight:700; font-size:1.125rem;">{{ $clinica->mediaAvaliacoes() }}</span>
                    </div>
                    <p style="color:#6B7280; font-size:0.875rem;">{{ $clinica->totalAvaliacoes() }} avaliacoes</p>
                </div>
            </div>

            @if ($clinica->descricao)
                <p style="color:#374151; line-height:1.7; font-size:1.0625rem;">{{ $clinica->descricao }}</p>
            @endif
        </section>

        {{-- Especialidades --}}
        <section class="card" style="padding:32px;" aria-label="Especialidades oferecidas">
            <h2 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:16px;">
                <span aria-hidden="true">&#x1F3E5;</span> Especialidades
            </h2>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                @foreach ($clinica->especialidades as $esp)
                    <span class="badge badge-blue" style="font-size:0.875rem; padding:6px 16px;">{{ $esp->nome }}</span>
                @endforeach
            </div>
        </section>

        {{-- Acessibilidade --}}
        <section class="card" style="padding:32px;" aria-label="Recursos de acessibilidade">
            <h2 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:16px;">
                <span aria-hidden="true">&#x2713;</span> Recursos de Acessibilidade
            </h2>
            @if ($clinica->servicosAcessibilidade->count() > 0)
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:12px;">
                    @foreach ($clinica->servicosAcessibilidade as $servico)
                        <div style="display:flex; align-items:flex-start; gap:12px; padding:16px; background:#D1FAE5; border:2px solid #A7F3D0; border-radius:12px;">
                            <span style="color:#047857; font-size:1.25rem; flex-shrink:0; margin-top:2px;" aria-hidden="true">&#x2713;</span>
                            <div>
                                <span style="font-weight:600; color:#065F46;">{{ $servico->nome }}</span>
                                @if ($servico->pivot->observacao)
                                    <p style="font-size:0.8125rem; color:#047857; margin-top:2px;">{{ $servico->pivot->observacao }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6B7280; padding:24px; text-align:center;">Nenhum recurso de acessibilidade cadastrado.</p>
            @endif
        </section>

        {{-- Avaliacoes --}}
        <section class="card" style="padding:32px;" aria-label="Avaliacoes dos pacientes">
            <h2 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px;">
                <span aria-hidden="true">&#x2B50;</span> Avaliacoes
            </h2>

            @if ($clinica->avaliacoes->count() > 0)
                <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:24px;">
                    @foreach ($clinica->avaliacoes as $avaliacao)
                        <div style="padding-bottom:20px; border-bottom:1px solid #F3F4F6;">
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                                <div style="width:40px; height:40px; background:#E0F2F1; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#009688;">
                                    {{ substr($avaliacao->paciente->nome, 0, 1) }}
                                </div>
                                <div>
                                    <span style="font-weight:600; color:#111827;">{{ $avaliacao->paciente->nome }}</span>
                                    <div class="star-rating" aria-label="Nota {{ $avaliacao->nota }} de 5" style="margin-top:2px;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $avaliacao->nota ? 'filled' : '' }}" aria-hidden="true" style="font-size:0.875rem;">&#x2605;</span>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @if ($avaliacao->comentario)
                                <p style="color:#374151; line-height:1.6;">{{ $avaliacao->comentario }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6B7280; padding:24px; text-align:center; border:1px dashed #D1D5DB; border-radius:12px;">
                    Nenhuma avaliacao ainda. Se o primeiro a avaliar!
                </p>
            @endif

            {{-- Formulario de avaliacao --}}
            @auth
                @if (Auth::user()->isPaciente())
                    <div style="padding-top:24px; border-top:2px solid #E5E7EB;">
                        <h3 style="font-weight:700; color:#111827; margin-bottom:16px;">Deixe sua avaliacao</h3>
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
                                                <span aria-hidden="true">&#x2605;</span>
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

                            <button type="submit" class="btn btn-primary">
                                Enviar Avaliacao
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </section>
    </div>

    {{-- Sidebar --}}
    <aside style="position:sticky; top:88px; display:flex; flex-direction:column; gap:20px;">
        {{-- Contato --}}
        <section class="card" style="padding:24px;" aria-label="Informacoes de contato">
            <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:16px;">
                <span aria-hidden="true">&#x1F4DE;</span> Contato
            </h2>
            <div style="display:flex; flex-direction:column; gap:12px; font-size:0.9375rem;">
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <span aria-hidden="true" style="color:#6B7280; margin-top:2px;">&#x1F4DE;</span>
                    <div>
                        <span style="color:#6B7280; font-size:0.8125rem; display:block;">Telefone</span>
                        <span style="font-weight:600; color:#111827;">{{ $clinica->telefone ?: 'Nao informado' }}</span>
                    </div>
                </div>
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <span aria-hidden="true" style="color:#6B7280; margin-top:2px;">&#x2709;</span>
                    <div>
                        <span style="color:#6B7280; font-size:0.8125rem; display:block;">E-mail</span>
                        <span style="font-weight:600; color:#111827;">{{ $clinica->email_contato ?: 'Nao informado' }}</span>
                    </div>
                </div>
                <div style="display:flex; align-items:flex-start; gap:10px;">
                    <span aria-hidden="true" style="color:#6B7280; margin-top:2px;">&#x1F4CD;</span>
                    <div>
                        <span style="color:#6B7280; font-size:0.8125rem; display:block;">Endereco</span>
                        <span style="font-weight:600; color:#111827;">{{ $clinica->enderecoCompleto() }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Horarios --}}
        <section class="card" style="padding:24px;" aria-label="Horarios disponiveis">
            <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:16px;">
                <span aria-hidden="true">&#x1F552;</span> Horarios
            </h2>
            @if ($clinica->horarios->count() > 0)
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach ($clinica->horarios->where('ativo', true) as $horario)
                        <div style="display:flex; justify-content:space-between; padding:8px 12px; background:#F9FAFB; border-radius:8px; font-size:0.9375rem;">
                            <span style="font-weight:600; color:#111827; text-transform:capitalize;">{{ $horario->dia_semana }}</span>
                            <span style="color:#4B5563;">{{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fim, 0, 5) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6B7280; font-size:0.9375rem; text-align:center; padding:16px;">Horarios nao cadastrados.</p>
            @endif
        </section>

        {{-- Agendar --}}
        @auth
            @if (Auth::user()->isPaciente())
                <section class="card" style="padding:24px;" aria-label="Solicitar agendamento">
                    <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:16px;">
                        <span aria-hidden="true">&#x1F4C5;</span> Agendar
                    </h2>
                    <form method="POST" action="{{ route('agendamentos.store') }}" aria-label="Formulario de agendamento">
                        @csrf
                        <input type="hidden" name="clinica_id" value="{{ $clinica->id }}">

                        <div class="form-group" style="margin-bottom:16px;">
                            <label for="data" class="form-label">Data</label>
                            <input type="date" id="data" name="data" required min="{{ date('Y-m-d') }}" class="form-input">
                        </div>

                        <div class="form-group" style="margin-bottom:16px;">
                            <label for="hora" class="form-label">Horario</label>
                            <input type="time" id="hora" name="hora" required class="form-input">
                        </div>

                        <div class="form-group" style="margin-bottom:20px;">
                            <label for="obs" class="form-label">Observacao (opcional)</label>
                            <textarea id="obs" name="observacao_paciente" rows="3" class="form-textarea"
                                      placeholder="Descreva sua necessidade..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            Solicitar Agendamento
                        </button>
                    </form>
                </section>
            @endif
        @else
            <div class="card" style="padding:24px; text-align:center; background:#E0F2F1; border-color:#80CBC4;">
                <p style="color:#00695C; font-weight:600; margin-bottom:12px;">Faca login como paciente para agendar.</p>
                <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;">
                    Entrar
                </a>
            </div>
        @endauth
    </aside>
</div>

@push('head')
<style>
    @media (max-width: 900px) {
        div[style*="grid-template-columns:1fr 380px"] {
            grid-template-columns: 1fr !important;
        }
        aside {
            position: static !important;
        }
    }
</style>
@endpush
@endsection
