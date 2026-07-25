@extends('layouts.app')

@section('titulo', 'Buscar Clinicas')

@section('conteudo')
<h1 style="font-size:1.875rem; font-weight:800; color:#111827; margin-bottom:6px;">
    <i class="bi bi-search" aria-hidden="true"></i> Buscar Clinicas
</h1>
<p style="color:#6B7280; margin-bottom:24px;">Encontre clinicas de fisioterapia acessiveis perto de voce</p>

{{-- Busca principal --}}
<form method="GET" action="{{ route('clinicas.index') }}" aria-label="Filtros de busca de clinicas">
    <div style="position:relative; margin-bottom:16px;">
        <i class="bi bi-search" style="position:absolute; left:18px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:1.125rem;" aria-hidden="true"></i>
        <input type="text" id="busca" name="busca" value="{{ request('busca') }}"
               placeholder="Buscar por nome, bairro ou cidade..." class="form-input"
               style="padding-left:48px; height:52px; font-size:1rem;">
    </div>

    {{-- Pills de especialidade (filtro rapido) --}}
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;" role="group" aria-label="Filtro rapido por especialidade">
        <button type="submit" name="especialidade" value=""
                class="pill-filtro {{ !request('especialidade') ? 'pill-ativo' : '' }}">
            Todas
        </button>
        @foreach ($especialidades as $esp)
            <button type="submit" name="especialidade" value="{{ $esp->id }}"
                    class="pill-filtro {{ request('especialidade') == $esp->id ? 'pill-ativo' : '' }}">
                {{ $esp->nome }}
            </button>
        @endforeach
    </div>

    {{-- Filtros avancados --}}
    <div class="card" style="padding:20px 24px; margin-bottom:28px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; align-items:end;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" id="cidade" name="cidade" value="{{ request('cidade') }}"
                       placeholder="Ex: Sao Paulo" class="form-input">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="acessibilidade" class="form-label">Acessibilidade</label>
                <select id="acessibilidade" name="acessibilidade" class="form-select">
                    <option value="">Todos os recursos</option>
                    @foreach ($servicos as $servico)
                        <option value="{{ $servico->id }}" {{ request('acessibilidade') == $servico->id ? 'selected' : '' }}>{{ $servico->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">
                    <i class="bi bi-search" aria-hidden="true"></i> Filtrar
                </button>
                <a href="{{ route('clinicas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</form>

{{-- Resultados --}}
@if ($clinicas->count() > 0)
    <p style="color:#4B5563; font-weight:600; margin-bottom:20px;">
        {{ $clinicas->total() }} {{ $clinicas->total() == 1 ? 'clinica encontrada' : 'clinicas encontradas' }}
    </p>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @foreach ($clinicas as $index => $clinica)
            <article class="clinica-lista-card">
                <div class="clinica-lista-avatar">
                    @if ($clinica->foto_capa)
                        <img src="{{ $clinica->foto_capa }}" alt="Foto da clinica {{ $clinica->nome_fantasia }}" loading="lazy">
                    @else
                        <img src="https://picsum.photos/seed/dailycare-lista{{ $clinica->id }}/160/160" alt="Foto da clinica {{ $clinica->nome_fantasia }}" loading="lazy">
                    @endif
                </div>

                <div class="clinica-lista-conteudo">
                    <div style="display:flex; align-items:start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                        <div>
                            <h2 style="font-size:1.1875rem; font-weight:700; color:#111827; margin-bottom:2px;">
                                {{ $clinica->nome_fantasia }}
                                <i class="bi bi-patch-check-fill" style="color:#009688; font-size:0.9rem;" aria-hidden="true"></i>
                            </h2>
                            <p style="color:#6B7280; font-size:0.875rem;">
                                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i> {{ $clinica->cidade }} - {{ $clinica->estado }}
                            </p>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <div class="star-rating" aria-label="Nota {{ $clinica->mediaAvaliacoes() }} de 5 estrelas">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= round($clinica->mediaAvaliacoes()) ? 'filled' : '' }}" aria-hidden="true">&#x2605;</span>
                                @endfor
                            </div>
                            <span style="font-size:0.8125rem; color:#6B7280;">
                                {{ number_format($clinica->mediaAvaliacoes(), 1) }} ({{ $clinica->totalAvaliacoes() }} avaliacoes)
                            </span>
                        </div>
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin:12px 0;">
                        @foreach ($clinica->especialidades->take(3) as $esp)
                            <span class="badge badge-blue">{{ $esp->nome }}</span>
                        @endforeach
                        @if ($clinica->especialidades->count() > 3)
                            <span class="badge badge-gray">+{{ $clinica->especialidades->count() - 3 }}</span>
                        @endif
                    </div>

                    <p style="color:#6B7280; font-size:0.9375rem; line-height:1.5; margin-bottom:14px; max-width:640px;">
                        {{ \Illuminate\Support\Str::limit($clinica->descricao, 140) }}
                    </p>

                    @php
                        $diasAbrev = ['segunda' => 'Seg', 'terca' => 'Ter', 'quarta' => 'Qua', 'quinta' => 'Qui', 'sexta' => 'Sex', 'sabado' => 'Sab', 'domingo' => 'Dom'];
                        $horariosAtivos = $clinica->horarios->where('ativo', true)->take(2);
                    @endphp

                    @if ($horariosAtivos->count() > 0)
                        <div class="clinica-horarios-grid">
                            @foreach ($horariosAtivos as $horario)
                                @php
                                    $inicio = \Carbon\Carbon::createFromFormat('H:i:s', $horario->hora_inicio);
                                    $fim = \Carbon\Carbon::createFromFormat('H:i:s', $horario->hora_fim);
                                    $slots = [];
                                    $cursor = $inicio->copy();
                                    while ($cursor->lt($fim) && count($slots) < 4) {
                                        $slots[] = $cursor->format('H:i');
                                        $cursor->addMinutes(90);
                                    }
                                @endphp
                                <div class="clinica-horarios-dia">
                                    <span class="clinica-horarios-dia-label">{{ $diasAbrev[$horario->dia_semana] ?? $horario->dia_semana }}</span>
                                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                        @foreach ($slots as $slot)
                                            <a href="{{ route('clinicas.show', $clinica) }}#agendar" class="horario-slot">{{ $slot }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; padding-top:14px; border-top:1px solid #F3F4F6;">
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            @foreach ($clinica->servicosAcessibilidade->take(3) as $acesso)
                                <span class="badge badge-green">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ $acesso->nome }}
                                </span>
                            @endforeach
                        </div>
                        <div style="display:flex; gap:10px; flex-shrink:0;">
                            <a href="mailto:{{ $clinica->email_contato }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-chat-dots" aria-hidden="true"></i> Enviar mensagem
                            </a>
                            <a href="{{ route('clinicas.show', $clinica) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-calendar-check" aria-hidden="true"></i> Ver perfil e agendar
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div style="margin-top:40px;">
        {{ $clinicas->links() }}
    </div>
@else
    <div class="card" style="padding:64px 32px; text-align:center;">
        <i class="bi bi-search" style="font-size:3.5rem; margin-bottom:16px; color:#D1D5DB; display:block;" aria-hidden="true"></i>
        <p style="color:#6B7280; font-size:1.125rem; margin-bottom:16px;">Nenhuma clinica encontrada com os filtros selecionados.</p>
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
            Limpar filtros e buscar novamente
        </a>
    </div>
@endif
@endsection
