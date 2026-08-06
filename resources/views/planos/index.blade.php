@extends('layouts.app')

@section('titulo', 'Planos')

@section('conteudo')
<div style="max-width:920px; margin:0 auto;">
    <div style="text-align:center; margin-bottom:32px;">
        <h1 style="font-size:1.75rem; font-weight:800; color:#111827;">
            <i class="bi bi-rocket-takeoff-fill" aria-hidden="true"></i> Espaco na DailyCare
        </h1>
        <p style="color:#6B7280; margin-top:8px;">
            Alugue seu espaco na plataforma pra divulgar sua clinica e ganhar mais visibilidade com os pacientes.
        </p>
    </div>

    @if (session('erro_plano'))
        <div class="alert-erro" style="margin-bottom:24px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> {{ session('erro_plano') }}
        </div>
    @endif

    @if ($assinaturaAtiva)
        <div class="plano-ativo-aviso">
            <div>
                <strong>Plano ativo: {{ $assinaturaAtiva->plano->nome }}</strong>
                <p>Valido ate {{ $assinaturaAtiva->data_fim->format('d/m/Y') }} &middot; cobranca {{ $assinaturaAtiva->periodo === 'anual' ? 'anual' : 'mensal' }}</p>
            </div>
            <form method="POST" action="{{ route('planos.cancelar') }}" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-secondary btn-sm">Cancelar assinatura</button>
            </form>
        </div>
    @endif

    {{-- Alterna entre cobranca mensal e anual --}}
    <div class="planos-periodo-toggle">
        <button type="button" id="btn-periodo-mensal" class="ativo" onclick="window.DailyCare.planos.mudarPeriodo('mensal')">Mensal</button>
        <button type="button" id="btn-periodo-anual" onclick="window.DailyCare.planos.mudarPeriodo('anual')">
            Anual <span class="planos-desconto-tag">2 meses gratis</span>
        </button>
    </div>

    <div class="planos-grid">
        @foreach ($planos as $plano)
            @php $ehAssinado = $assinaturaAtiva && $assinaturaAtiva->plano_id === $plano->id; @endphp
            <article class="plano-card {{ $plano->prioridade_busca ? 'plano-card-destaque' : '' }}">
                @if ($plano->prioridade_busca)
                    <span class="plano-selo">Mais popular</span>
                @endif

                <h2>{{ $plano->nome }}</h2>
                <p class="plano-descricao">{{ $plano->descricao }}</p>

                <div class="plano-preco">
                    <span class="plano-preco-mensal">
                        <strong>R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}</strong>/mes
                    </span>
                    <span class="plano-preco-anual" style="display:none;">
                        <strong>R$ {{ number_format($plano->preco_anual / 12, 2, ',', '.') }}</strong>/mes
                        <small>cobrado R$ {{ number_format($plano->preco_anual, 2, ',', '.') }} por ano</small>
                    </span>
                </div>

                <ul class="plano-beneficios">
                    @foreach ($plano->beneficios ?? [] as $beneficio)
                        <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ $beneficio }}</li>
                    @endforeach
                </ul>

                @if ($ehAssinado)
                    <button type="button" class="btn btn-secondary" style="width:100%; justify-content:center;" disabled>
                        <i class="bi bi-check-lg" aria-hidden="true"></i> Plano atual
                    </button>
                @else
                    <form method="POST" action="{{ route('planos.assinar', $plano) }}">
                        @csrf
                        <input type="hidden" name="periodo" id="periodo-input-{{ $plano->id }}" value="mensal">
                        <button type="submit" class="btn {{ $plano->prioridade_busca ? 'btn-accent' : 'btn-primary' }}" style="width:100%; justify-content:center;">
                            {{ $assinaturaAtiva ? 'Trocar para este plano' : 'Assinar agora' }}
                        </button>
                    </form>
                @endif
            </article>
        @endforeach
    </div>

    <p style="text-align:center; color:#9CA3AF; font-size:0.8125rem; margin-top:24px;">
        <i class="bi bi-info-circle" aria-hidden="true"></i>
        Simulacao de assinatura para fins de demonstracao. Nenhuma cobranca real e feita.
    </p>
</div>

<script>
    window.DailyCare = window.DailyCare || {};
    window.DailyCare.planos = {
        mudarPeriodo(periodo) {
            const mensal = periodo === 'mensal';
            document.getElementById('btn-periodo-mensal').classList.toggle('ativo', mensal);
            document.getElementById('btn-periodo-anual').classList.toggle('ativo', !mensal);

            document.querySelectorAll('.plano-preco-mensal').forEach(function (el) {
                el.style.display = mensal ? 'block' : 'none';
            });
            document.querySelectorAll('.plano-preco-anual').forEach(function (el) {
                el.style.display = mensal ? 'none' : 'block';
            });
            document.querySelectorAll('input[id^="periodo-input-"]').forEach(function (input) {
                input.value = periodo;
            });
        }
    };
</script>
@endsection
