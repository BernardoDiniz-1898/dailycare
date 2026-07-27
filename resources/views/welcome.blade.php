@extends('layouts.app')

@section('titulo', 'Bem-vindo')

@section('conteudo')

{{-- HERO --}}
<section aria-label="Apresentacao do Daily Care" class="hero-section">
        <div class="badge-accent">
            <i class="bi bi-heart-pulse-fill" aria-hidden="true"></i> Marketplace de Fisioterapia Acessivel
        </div>

        <h1 style="font-size:clamp(2rem, 5vw, 3.25rem); font-weight:800; color:#FFFFFF; line-height:1.2; margin-bottom:16px; letter-spacing:-0.02em;">
            Encontre clinicas de fisioterapia<br>
            <span style="color:#4DB6AC;">100% acessiveis</span>
        </h1>

        <p style="font-size:1.125rem; color:#D5E3E1; max-width:560px; margin:0 auto 36px; line-height:1.6;">
            Conectamos pacientes com deficiencias motoras a clinicas independentes validadas com criterios reais de acessibilidade fisica.
        </p>

        <form method="GET" action="{{ route('clinicas.index') }}" class="hero-search" aria-label="Busca rapida de clinicas">
            <div class="hero-search-grid">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="hero-busca" class="form-label">Buscar</label>
                    <input type="text" id="hero-busca" name="busca"
                           placeholder="Nome, bairro, especialidade..." class="form-input">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="hero-cidade" class="form-label">Cidade</label>
                    <input type="text" id="hero-cidade" name="cidade"
                           placeholder="Ex: Sao Paulo" class="form-input">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label aria-hidden="true" class="form-label" style="visibility:hidden;">Buscar</label>
                    <button type="submit" class="btn btn-primary" style="width:100%; height:48px; white-space:nowrap;">
                        <i class="bi bi-search" aria-hidden="true"></i> Buscar
                    </button>
                </div>
            </div>
        </form>

        <div class="hero-trust-row">
            <span><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Clinicas com CNPJ verificado</span>
            <span><i class="bi bi-shield-check" aria-hidden="true"></i> WCAG 2.1 AA</span>
            <span><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> Busca por localizacao</span>
        </div>
</section>

{{-- COMO FUNCIONA --}}
<section aria-label="Como funciona" style="margin-bottom:64px;">
    <p class="secao-eyebrow">Como funciona</p>
    <h2 class="secao-titulo">Sua jornada em 3 passos simples</h2>

    <div class="passo-lista">
        <article class="passo-item">
            <div class="passo-foto">
                <img src="https://picsum.photos/seed/dailycare-passo1/600/400" alt="Pessoa pesquisando em um notebook" loading="lazy">
            </div>
            <div class="passo-texto">
                <span class="passo-numero">01</span>
                <h3><i class="bi bi-search" aria-hidden="true"></i> Busque</h3>
                <p>Pesquise por especialidade, cidade ou CEP. Filtre por recursos de acessibilidade que voce precisa: rampa, banheiro adaptado, elevador e mais.</p>
            </div>
        </article>

        <article class="passo-item passo-invertido">
            <div class="passo-foto">
                <img src="https://picsum.photos/seed/dailycare-passo2/600/400" alt="Fisioterapeuta avaliando estrutura da clinica" loading="lazy">
            </div>
            <div class="passo-texto">
                <span class="passo-numero">02</span>
                <h3><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Valide</h3>
                <p>Verifique a infraestrutura real da clinica, veja as especialidades oferecidas e a avaliacao de outros pacientes antes de decidir.</p>
            </div>
        </article>

        <article class="passo-item">
            <div class="passo-foto">
                <img src="https://picsum.photos/seed/dailycare-passo3/600/400" alt="Pessoa agendando consulta pelo celular" loading="lazy">
            </div>
            <div class="passo-texto">
                <span class="passo-numero">03</span>
                <h3><i class="bi bi-calendar-check-fill" aria-hidden="true"></i> Agende</h3>
                <p>Solicite horarios disponiveis e agende suas sessoes de forma autonoma, acessivel e sem burocracia.</p>
            </div>
        </article>
    </div>
</section>

{{-- CLINICAS EM DESTAQUE --}}
@if($clinicasDestaque->count() > 0)
<section aria-label="Clinicas em destaque" style="margin-bottom:64px;">
    <p class="secao-eyebrow" style="text-align:center;">Clinicas em destaque</p>
    <h2 class="secao-titulo" style="text-align:center;">Clinicas verificadas e avaliadas</h2>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; max-width:1100px; margin:0 auto;">
        @foreach($clinicasDestaque as $index => $clinica)
            <article class="card clinica-destaque-card">
                @php
                    $fotosClinica = [
                        'https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=500&q=80',
                        'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80',
                        'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=500&q=80',
                        'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=500&q=80',
                    ];
                    $fotoClinica = $clinica->foto_capa ?: $fotosClinica[$clinica->id % count($fotosClinica)];
                @endphp
                <img src="{{ $fotoClinica }}" alt="Estrutura da clinica {{ $clinica->nome_fantasia }}" loading="lazy" style="width:100%; height:160px; object-fit:cover;">
                <div style="padding:20px;">
                    <h3 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:4px;">
                        {{ $clinica->nome_fantasia }} <i class="bi bi-patch-check-fill" style="color:#009688; font-size:0.9rem;" aria-hidden="true"></i>
                    </h3>
                    <p style="color:#6B7280; font-size:0.875rem; margin-bottom:12px;">
                        <i class="bi bi-geo-alt-fill" aria-hidden="true"></i> {{ $clinica->cidade }} - {{ $clinica->estado }}
                    </p>
                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:16px;">
                        @foreach($clinica->especialidades->take(2) as $esp)
                            <span class="badge badge-blue">{{ $esp->nome }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('clinicas.show', $clinica->id) }}" class="btn btn-primary btn-sm" style="width:100%; justify-content:center;">
                        <i class="bi bi-calendar-check" aria-hidden="true"></i> Ver clinica
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

{{-- FAIXA DE ESTATISTICAS --}}
<section aria-label="Estatisticas" class="stats-band">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:24px; max-width:800px; margin:0 auto; text-align:center;">
        <div>
            <p class="stat-number">&lt;1%</p>
            <p class="stat-label">dos sites brasileiros sao acessiveis</p>
        </div>
        <div>
            <p class="stat-number">100%</p>
            <p class="stat-label">WCAG 2.1 AA no Daily Care</p>
        </div>
        <div>
            <p class="stat-number">18.6M</p>
            <p class="stat-label">PcDs no Brasil</p>
        </div>
    </div>
</section>

{{-- CTA FINAL --}}
<section aria-label="Comece agora" class="cta-final">
    <h2 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:12px;">Pronto para comecar?</h2>
    <p style="color:#4B5563; margin-bottom:28px; font-size:1.0625rem;">Encontre uma clinica acessivel perto de voce em poucos cliques.</p>
    <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-search" aria-hidden="true"></i> Buscar Clinicas
        </a>
        @guest
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-person-plus-fill" aria-hidden="true"></i> Criar conta gratis
            </a>
        @endguest
    </div>
</section>
@endsection
