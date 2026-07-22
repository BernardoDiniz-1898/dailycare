@extends('layouts.app')

@section('titulo', 'Bem-vindo')

@section('conteudo')

{{-- HERO --}}
<section aria-label="Apresentacao do Daily Care" class="hero-section">
    <div class="badge-accent">
        <span aria-hidden="true">&#x1F3E5;</span> Marketplace de Fisioterapia Acessivel
    </div>

    <h1 style="font-size:clamp(2rem, 5vw, 3.25rem); font-weight:800; color:#FFFFFF; line-height:1.2; margin-bottom:16px; letter-spacing:-0.02em;">
        Encontre clinicas de fisioterapia<br>
        <span style="color:#4DB6AC;">100% acessiveis</span>
    </h1>

    <p style="font-size:1.125rem; color:#B8CBC9; max-width:560px; margin:0 auto 36px; line-height:1.6;">
        Conectamos pacientes com deficiencias motoras a clinicas independentes validadas com criterios reais de acessibilidade fisica.
    </p>

    {{-- Busca embutida no hero --}}
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
                    <span aria-hidden="true">&#x1F50D;</span> Buscar
                </button>
            </div>
        </div>
    </form>

    @guest
        <p style="margin-top:24px;">
            <a href="{{ route('register') }}" style="color:#4DB6AC; font-weight:600; text-decoration:underline; text-underline-offset:4px;">
                Ainda nao tem conta? Cadastre-se gratis
            </a>
        </p>
    @endguest
</section>

{{-- COMO FUNCIONA --}}
<section aria-label="Como funciona" style="margin-bottom:64px;">
    <h2 style="text-align:center; font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:40px;">
        Como funciona
    </h2>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; max-width:1000px; margin:0 auto;">
        <article class="card" style="padding:32px; text-align:center;">
            <div class="icon-circle" aria-hidden="true">&#x1F50D;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">1. Busque</h3>
            <p style="color:#4B5563; line-height:1.6;">Pesquise por especialidade, cidade ou CEP. Filtre por recursos de acessibilidade que voce precisa.</p>
        </article>

        <article class="card" style="padding:32px; text-align:center;">
            <div class="icon-circle" aria-hidden="true">&#x2705;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">2. Valide</h3>
            <p style="color:#4B5563; line-height:1.6;">Verifique a infraestrutura real da clinica: rampa, banheiro adaptado, elevador, portas largas e mais.</p>
        </article>

        <article class="card" style="padding:32px; text-align:center;">
            <div class="icon-circle" aria-hidden="true">&#x1F4C5;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">3. Agende</h3>
            <p style="color:#4B5563; line-height:1.6;">Solicite horarios disponiveis e agende suas sessoes de forma autonoma e acessivel.</p>
        </article>
    </div>
</section>

{{-- ACESSIBILIDADE TOTAL --}}
<section aria-label="Recursos de acessibilidade" style="background:#E0F2F1; border:2px solid #80CBC4; border-radius:20px; padding:48px 40px; max-width:1000px; margin:0 auto;">
    <h2 style="text-align:center; font-size:1.75rem; font-weight:800; color:#00695C; margin-bottom:12px;">
        <span aria-hidden="true">&#x2713;</span> Acessibilidade Total
    </h2>
    <p style="text-align:center; color:#4B5563; margin-bottom:32px; font-size:1.0625rem;">
        Todos os recursos do site seguem as diretrizes WCAG 2.1 Nivel AA
    </p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
        @foreach ([
            'Navegacao por Teclado',
            'Alto Contraste',
            'Redimensionamento de Fontes',
            'Traducao em Libras',
            'Foco Visivel',
            'Contraste Dinamico',
        ] as $recurso)
            <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #80CBC4;">
                <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
                <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">{{ $recurso }}</span>
            </div>
        @endforeach
    </div>
</section>

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
            <span aria-hidden="true">&#x1F50D;</span> Buscar Clinicas
        </a>
        @guest
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                <span aria-hidden="true">&#x2795;</span> Criar conta gratis
            </a>
        @endguest
    </div>
</section>
@endsection
