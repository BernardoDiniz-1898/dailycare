@extends('layouts.app')

@section('titulo', 'Buscar Clinicas')

@section('conteudo')
<h1 style="font-size:1.875rem; font-weight:800; color:var(--color-text); margin-bottom:6px;">
    <i class="bi bi-search" aria-hidden="true"></i> Buscar Clinicas
</h1>
<p style="color:var(--color-text-secondary); margin-bottom:24px;">Encontre clinicas de fisioterapia acessiveis perto de voce</p>

{{-- Busca principal --}}
<form method="GET" action="{{ route('clinicas.index') }}" aria-label="Filtros de busca de clinicas" id="form-busca-clinicas">
    <div style="position:relative; margin-bottom:16px;">
        <i class="bi bi-search" style="position:absolute; left:18px; top:50%; transform:translateY(-50%); color:var(--color-text-secondary); font-size:1.125rem;" aria-hidden="true"></i>
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
    <div class="card" style="padding:20px 24px; margin-bottom:20px;">
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

    {{-- Preserva ordenacao ao filtrar --}}
    <input type="hidden" name="ordenar" value="{{ request('ordenar', 'recentes') }}">
</form>

{{-- Barra de resultados: contagem + ordenacao + grade/lista --}}
<div class="resultados-barra">
    <p class="resultados-contagem">
        {{ $clinicas->total() }} {{ $clinicas->total() == 1 ? 'clinica encontrada' : 'clinicas encontradas' }}
    </p>

    <div class="resultados-controles">
        <label for="ordenar" class="sr-only">Ordenar por</label>
        <select id="ordenar" class="form-select resultados-ordenar" onchange="window.DailyCare.buscaClinicas.ordenar(this.value)">
            <option value="recentes" {{ request('ordenar', 'recentes') == 'recentes' ? 'selected' : '' }}>Mais recentes</option>
            <option value="avaliacao" {{ request('ordenar') == 'avaliacao' ? 'selected' : '' }}>Melhor avaliacao</option>
            <option value="avaliacoes_count" {{ request('ordenar') == 'avaliacoes_count' ? 'selected' : '' }}>Mais avaliadas</option>
        </select>

        <div class="visualizacao-toggle" role="group" aria-label="Modo de visualizacao">
            <button type="button" id="btn-visualizacao-lista" class="visualizacao-btn ativo"
                    onclick="window.DailyCare.buscaClinicas.setVisualizacao('lista')" aria-label="Ver em lista" aria-pressed="true">
                <i class="bi bi-list-ul" aria-hidden="true"></i>
            </button>
            <button type="button" id="btn-visualizacao-grade" class="visualizacao-btn"
                    onclick="window.DailyCare.buscaClinicas.setVisualizacao('grade')" aria-label="Ver em grade" aria-pressed="false">
                <i class="bi bi-grid-3x3-gap" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>

{{-- Resultados --}}
@if ($clinicas->count() > 0)
    <div id="resultados-container" class="resultados-lista">
        @foreach ($clinicas as $clinica)
            @php
                $especialidadePrincipal = $clinica->especialidades->first();
                $fotosClinicaLista = [
                    'https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=300&q=80',
                    'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=300&q=80',
                    'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=300&q=80',
                    'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=300&q=80',
                ];
                $fotoClinicaLista = $clinica->foto_capa ?: $fotosClinicaLista[$clinica->id % count($fotosClinicaLista)];
            @endphp
            @php
                $mapaDia = ['domingo' => 0, 'segunda' => 1, 'terca' => 2, 'quarta' => 3, 'quinta' => 4, 'sexta' => 5, 'sabado' => 6];
                $abrevPorNumero = [0 => 'DOM', 1 => 'SEG', 2 => 'TER', 3 => 'QUA', 4 => 'QUI', 5 => 'SEX', 6 => 'SAB'];
                $hoje = \Carbon\Carbon::today();

                $proximosHorarios = collect($clinica->horarios->where('ativo', true))
                    ->map(function ($horario) use ($mapaDia, $abrevPorNumero, $hoje) {
                        $diaNum = $mapaDia[$horario->dia_semana] ?? null;
                        if ($diaNum === null) {
                            return null;
                        }

                        $diferencaDias = ($diaNum - $hoje->dayOfWeek + 7) % 7;
                        $data = $hoje->copy()->addDays($diferencaDias);

                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                        $fim = \Carbon\Carbon::parse($horario->hora_fim);
                        $slots = [];
                        $cursor = $inicio->copy();
                        while ($cursor->lt($fim) && count($slots) < 3) {
                            $slots[] = $cursor->format('H:i');
                            $cursor->addMinutes(90);
                        }

                        return [
                            'data' => $data,
                            'label' => $abrevPorNumero[$diaNum] . ', DIA ' . $data->format('d'),
                            'slots' => $slots,
                        ];
                    })
                    ->filter()
                    ->sortBy('data')
                    ->take(3)
                    ->values();

                    $disponivel = $proximosHorarios->count() > 0;
                    $especialidadesBadges = $clinica->especialidades->count() > 1
                        ? $clinica->especialidades->skip(1)
                        : $clinica->especialidades;
            @endphp
            <article class="clinica-lista-card">
                <div class="clinica-card-tira {{ $disponivel ? 'disponivel' : 'indisponivel' }}"></div>

                <div class="clinica-card-principal">
                    <div class="clinica-lista-avatar-wrap">
                        <div class="clinica-lista-avatar">
                            <img src="{{ $fotoClinicaLista }}" alt="Foto da clinica {{ $clinica->nome_fantasia }}" loading="lazy">
                            <span class="dot-disponibilidade {{ $disponivel ? 'disponivel' : 'indisponivel' }}"
                                  title="{{ $disponivel ? 'Disponivel para agendamento' : 'Sem horarios no momento' }}">
                                @if ($disponivel)
                                    <span class="dot-pulso" aria-hidden="true"></span>
                                @endif
                            </span>
                        </div>
                        <button type="button" class="btn-favorito" data-favorito="{{ $clinica->id }}"
                                aria-pressed="false" title="Favoritar clinica"
                                onclick="window.DailyCare.buscaClinicas.alternarFavorito(this)">
                            <i class="bi bi-heart" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="clinica-lista-conteudo">
                        <div class="clinica-lista-topo">
                            <div>
                                <div class="clinica-lista-nome-linha">
                                    <h2 class="clinica-lista-nome">{{ $clinica->nome_fantasia }}</h2>
                                    <span class="icone-verificado" title="Clinica verificada"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
                                    @if ($disponivel)
                                        <span class="badge-disponivel"><span class="dot" aria-hidden="true"></span> Disponivel</span>
                                    @else
                                        <span class="badge-indisponivel">Sem vagas agora</span>
                                    @endif
                                </div>
                                @if ($especialidadePrincipal)
                                    <p class="clinica-lista-especialidade">{{ $especialidadePrincipal->nome }}</p>
                                @endif
                                <div class="clinica-lista-meta">
                                    @if ($clinica->usuario && $clinica->usuario->crefito)
                                        <span><i class="bi bi-award" aria-hidden="true"></i> {{ $clinica->usuario->crefito }}</span>
                                    @endif
                                    @if ($clinica->atendimentos_concluidos_count > 0)
                                        <span><i class="bi bi-trending-up" aria-hidden="true"></i> {{ $clinica->atendimentos_concluidos_count }} sessoes realizadas</span>
                                    @endif
                                    <span><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> {{ $clinica->cidade }} - {{ $clinica->estado }}</span>
                                </div>
                            </div>

                            <div class="clinica-lista-rating">
                                <div class="star-rating" aria-label="Nota {{ $clinica->mediaAvaliacoes() }} de 5 estrelas">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= round($clinica->mediaAvaliacoes()) ? 'filled' : '' }}" aria-hidden="true"><i class="bi bi-star-fill"></i></span>
                                    @endfor
                                </div>
                                <span class="clinica-lista-rating-texto">
                                    <strong>{{ number_format($clinica->mediaAvaliacoes(), 1) }}</strong> ({{ $clinica->totalAvaliacoes() }} avaliacoes)
                                </span>
                            </div>
                        </div>

                        @if ($especialidadesBadges->count() > 0)
                            <div class="clinica-lista-tags">
                                @foreach ($especialidadesBadges->take(3) as $esp)
                                    <span class="clinica-lista-tag">#{{ $esp->nome }}</span>
                                @endforeach
                                @if ($especialidadesBadges->count() > 3)
                                    <span class="clinica-lista-tag">+{{ $especialidadesBadges->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        <p class="clinica-lista-descricao">
                            {{ \Illuminate\Support\Str::limit($clinica->descricao, 140) }}
                        </p>

                        <div class="clinica-lista-oferece">
                            <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                                <span class="pill-modalidade"><span class="dot" aria-hidden="true"></span> Presencial</span>
                                @if ($clinica->servicosAcessibilidade->count() > 0)
                                    <span class="pill-modalidade"><i class="bi bi-universal-access" aria-hidden="true"></i> Acessivel</span>
                                @endif
                                @if ($clinica->preco_sessao)
                                    <span class="clinica-lista-preco">R$ {{ number_format($clinica->preco_sessao, 2, ',', '.') }}<small>/sessao</small></span>
                                @endif
                            </div>

                            <div class="clinica-lista-acoes">
                                @if ($proximosHorarios->count() > 0)
                                    <button type="button" class="btn btn-secondary btn-sm btn-ver-agenda"
                                            data-alvo="horarios-painel-{{ $clinica->id }}" aria-expanded="false"
                                            onclick="window.DailyCare.buscaClinicas.alternarAgenda(this)">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i> Ver agenda
                                    </button>
                                @endif
                                <a href="{{ auth()->check() && auth()->user()->isPaciente() ? route('chat.iniciar', $clinica) : route('login') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-chat-dots" aria-hidden="true"></i> Enviar mensagem
                                </a>
                                <a href="{{ route('clinicas.show', $clinica) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-calendar-check" aria-hidden="true"></i> Ver perfil e agendar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($proximosHorarios->count() > 0)
                    <aside class="clinica-card-horarios-painel" id="horarios-painel-{{ $clinica->id }}" aria-label="Proximos horarios de {{ $clinica->nome_fantasia }}">
                        <p class="clinica-horarios-titulo">Proximos horarios</p>
                        @foreach ($proximosHorarios as $ph)
                            <div class="clinica-horarios-dia-bloco">
                                <span class="clinica-horarios-dia-label">{{ $ph['label'] }}</span>
                                <div class="horarios-slots-wrap">
                                    @foreach ($ph['slots'] as $slot)
                                        <a href="{{ route('clinicas.show', $clinica) }}#agendar" class="horario-slot">{{ $slot }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <p class="clinica-horarios-dica">Clique em um horario para agendar</p>
                    </aside>
                @endif
            </article>
        @endforeach
    </div>

    <div style="margin-top:40px;">
        {{ $clinicas->links() }}
    </div>
@else
    <div class="estado-vazio">
        <i class="bi bi-search" aria-hidden="true"></i>
        <p>Nenhuma clinica encontrada com os filtros selecionados.</p>
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
            Limpar filtros e buscar novamente
        </a>
    </div>
@endif

<script>
    window.DailyCare = window.DailyCare || {};
    window.DailyCare.buscaClinicas = {
        ordenar(valor) {
            const form = document.getElementById('form-busca-clinicas');
            form.querySelector('input[name="ordenar"]').value = valor;
            form.submit();
        },
        alternarAgenda(botao) {
            const alvo = document.getElementById(botao.dataset.alvo);
            if (!alvo) return;
            const visivel = alvo.classList.toggle('visivel');
            botao.setAttribute('aria-expanded', visivel ? 'true' : 'false');
            const icone = botao.querySelector('i');
            if (icone) {
                icone.className = visivel ? 'bi bi-chevron-up' : 'bi bi-calendar3';
            }
        },
        alternarFavorito(botao) {
            const id = botao.dataset.favorito;
            const ativo = !botao.classList.contains('ativo');
            botao.classList.toggle('ativo', ativo);
            botao.setAttribute('aria-pressed', String(ativo));
            botao.querySelector('i').className = ativo ? 'bi bi-heart-fill' : 'bi bi-heart';

            let lista = JSON.parse(localStorage.getItem('dc-favoritos-clinicas') || '[]');
            if (ativo && !lista.includes(id)) lista.push(id);
            if (!ativo) lista = lista.filter(x => x !== id);
            localStorage.setItem('dc-favoritos-clinicas', JSON.stringify(lista));
        },
        setVisualizacao(modo) {
            const container = document.getElementById('resultados-container');
            const btnLista = document.getElementById('btn-visualizacao-lista');
            const btnGrade = document.getElementById('btn-visualizacao-grade');
            if (!container) return;

            if (modo === 'grade') {
                container.classList.remove('resultados-lista');
                container.classList.add('resultados-grade');
                btnGrade.classList.add('ativo');
                btnLista.classList.remove('ativo');
                btnGrade.setAttribute('aria-pressed', 'true');
                btnLista.setAttribute('aria-pressed', 'false');
            } else {
                container.classList.remove('resultados-grade');
                container.classList.add('resultados-lista');
                btnLista.classList.add('ativo');
                btnGrade.classList.remove('ativo');
                btnLista.setAttribute('aria-pressed', 'true');
                btnGrade.setAttribute('aria-pressed', 'false');
            }
            sessionStorage.setItem('dc-visualizacao-clinicas', modo);
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const salvo = sessionStorage.getItem('dc-visualizacao-clinicas');
        if (salvo === 'grade') {
            window.DailyCare.buscaClinicas.setVisualizacao('grade');
        }

        const favoritos = JSON.parse(localStorage.getItem('dc-favoritos-clinicas') || '[]');
        document.querySelectorAll('.btn-favorito').forEach(function (btn) {
            if (favoritos.includes(btn.dataset.favorito)) {
                btn.classList.add('ativo');
                btn.setAttribute('aria-pressed', 'true');
                btn.querySelector('i').className = 'bi bi-heart-fill';
            }
        });
    });
</script>
@endsection
