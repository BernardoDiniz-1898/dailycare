@extends('layouts.app')

@section('titulo', 'Bem-vindo')

@section('conteudo')
<section aria-label="Apresentacao do Daily Care" style="text-align:center; padding:80px 0 60px;">
    <div style="display:inline-flex; align-items:center; gap:12px; background:#EFF6FF; color:#1E40AF; padding:8px 20px; border-radius:9999px; font-size:0.875rem; font-weight:600; margin-bottom:24px;">
        <span aria-hidden="true">&#x1F3E5;</span> Marketplace de Fisioterapia Acessivel
    </div>

    <h1 style="font-size:clamp(2rem, 5vw, 3.5rem); font-weight:800; color:#111827; line-height:1.2; margin-bottom:20px; letter-spacing:-0.02em;">
        Encontre clinicas de fisioterapia<br>
        <span style="color:#1A56DB;">100% acessiveis</span>
    </h1>

    <p style="font-size:1.25rem; color:#4B5563; max-width:640px; margin:0 auto 40px; line-height:1.6;">
        Conectamos pacientes com deficiencias motoras a clinicas independentes validadas com criterios reais de acessibilidade fisica.
    </p>

    <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary btn-lg">
            <span aria-hidden="true">&#x1F50D;</span> Buscar Clinicas
        </a>
        @guest
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                <span aria-hidden="true">&#x2795;</span> Cadastrar-se
            </a>
        @endguest
    </div>
</section>

<section aria-label="Como funciona" style="margin-bottom:64px;">
    <h2 style="text-align:center; font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:40px;">
        Como funciona
    </h2>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; max-width:1000px; margin:0 auto;">
        <article class="card" style="padding:32px; text-align:center;">
            <div style="font-size:3rem; margin-bottom:16px;" aria-hidden="true">&#x1F50D;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">1. Busque</h3>
            <p style="color:#4B5563; line-height:1.6;">Pesquise por especialidade, cidade ou CEP. Filtre por recursos de acessibilidade que voce precisa.</p>
        </article>

        <article class="card" style="padding:32px; text-align:center;">
            <div style="font-size:3rem; margin-bottom:16px;" aria-hidden="true">&#x2705;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">2. Valide</h3>
            <p style="color:#4B5563; line-height:1.6;">Verifique a infraestrutura real da clinica: rampa, banheiro adaptado, elevador, portas largas e mais.</p>
        </article>

        <article class="card" style="padding:32px; text-align:center;">
            <div style="font-size:3rem; margin-bottom:16px;" aria-hidden="true">&#x1F4C5;</div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:8px;">3. Agende</h3>
            <p style="color:#4B5563; line-height:1.6;">Solicite horarios disponiveis e agende suas sessoes de forma autonoma e acessivel.</p>
        </article>
    </div>
</section>

<section aria-label="Recursos de acessibilidade" style="background:#EFF6FF; border:2px solid #BFDBFE; border-radius:20px; padding:48px 40px; max-width:1000px; margin:0 auto;">
    <h2 style="text-align:center; font-size:1.75rem; font-weight:800; color:#1E40AF; margin-bottom:12px;">
        <span aria-hidden="true">&#x2713;</span> Acessibilidade Total
    </h2>
    <p style="text-align:center; color:#4B5563; margin-bottom:32px; font-size:1.0625rem;">
        Todos os recursos do site seguem as diretrizes WCAG 2.1 Nivel AA
    </p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Navegacao por Teclado</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Alto Contraste</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Redimensionamento de Fontes</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Traducao em Libras</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Foco Visivel</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#FFFFFF; border-radius:12px; border:1px solid #BFDBFE;">
            <span style="color:#047857; font-size:1.25rem;" aria-hidden="true">&#x2713;</span>
            <span style="font-weight:600; color:#1F2937; font-size:0.9375rem;">Contraste Dinamico</span>
        </div>
    </div>
</section>

<section aria-label="Estatisticas" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:24px; max-width:800px; margin:64px auto 0; text-align:center;">
    <div>
        <p style="font-size:2.5rem; font-weight:800; color:#1A56DB;">&lt;1%</p>
        <p style="color:#6B7280; font-size:0.9375rem;">dos sites brasileiros sao acessiveis</p>
    </div>
    <div>
        <p style="font-size:2.5rem; font-weight:800; color:#047857;">100%</p>
        <p style="color:#6B7280; font-size:0.9375rem;">WCAG 2.1 AA no Daily Care</p>
    </div>
    <div>
        <p style="font-size:2.5rem; font-weight:800; color:#B91C1C;">18.6M</p>
        <p style="color:#6B7280; font-size:0.9375rem;">PcDs no Brasil</p>
    </div>
</section>
@endsection
