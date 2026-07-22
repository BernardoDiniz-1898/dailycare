@extends('layouts.app')

@section('titulo', 'Buscar Clinicas')

@section('conteudo')
<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:24px;">
    <span aria-hidden="true">&#x1F50D;</span> Buscar Clinicas
</h1>

{{-- Filtros --}}
<form method="GET" action="{{ route('clinicas.index') }}" class="card" style="padding:24px; margin-bottom:32px;" aria-label="Filtros de busca de clinicas">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
        <div class="form-group">
            <label for="busca" class="form-label">Buscar</label>
            <input type="text" id="busca" name="busca" value="{{ request('busca') }}"
                   placeholder="Nome, bairro, cidade..." class="form-input">
        </div>

        <div class="form-group">
            <label for="especialidade" class="form-label">Especialidade</label>
            <select id="especialidade" name="especialidade" class="form-select">
                <option value="">Todas as especialidades</option>
                @foreach ($especialidades as $esp)
                    <option value="{{ $esp->id }}" {{ request('especialidade') == $esp->id ? 'selected' : '' }}>{{ $esp->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" id="cidade" name="cidade" value="{{ request('cidade') }}"
                   placeholder="Ex: Sao Paulo" class="form-input">
        </div>

        <div class="form-group">
            <label for="acessibilidade" class="form-label">Acessibilidade</label>
            <select id="acessibilidade" name="acessibilidade" class="form-select">
                <option value="">Todos os recursos</option>
                @foreach ($servicos as $servico)
                    <option value="{{ $servico->id }}" {{ request('acessibilidade') == $servico->id ? 'selected' : '' }}>{{ $servico->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <span aria-hidden="true">&#x1F50D;</span> Buscar
        </button>
        <a href="{{ route('clinicas.index') }}" class="btn btn-secondary">
            Limpar Filtros
        </a>
    </div>
</form>

{{-- Resultados --}}
@if ($clinicas->count() > 0)
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:24px;">
        @foreach ($clinicas as $clinica)
            <a href="{{ route('clinicas.show', $clinica) }}" class="card card-link" aria-label="Ver perfil da clinica {{ $clinica->nome_fantasia }}">
                <div style="height:180px; background:linear-gradient(135deg, #E0F2F1, #E0F2F1); display:flex; align-items:center; justify-content:center;">
                    @if ($clinica->foto_capa)
                        <img src="{{ $clinica->foto_capa }}" alt="Foto da clinica {{ $clinica->nome_fantasia }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <span style="font-size:4rem; color:#80CBC4;" aria-hidden="true">&#x1F3E5;</span>
                    @endif
                </div>
                <div style="padding:20px;">
                    <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:4px;">
                        {{ $clinica->nome_fantasia }}
                    </h2>
                    <p style="color:#6B7280; font-size:0.875rem; margin-bottom:12px;">
                        <span aria-hidden="true">&#x1F4CD;</span> {{ $clinica->cidade }} - {{ $clinica->estado }}
                    </p>

                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px;">
                        @foreach ($clinica->especialidades->take(3) as $esp)
                            <span class="badge badge-blue">{{ $esp->nome }}</span>
                        @endforeach
                        @if ($clinica->especialidades->count() > 3)
                            <span class="badge badge-gray">+{{ $clinica->especialidades->count() - 3 }}</span>
                        @endif
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px;">
                        @foreach ($clinica->servicosAcessibilidade->take(4) as $acesso)
                            <span class="badge badge-green">
                                <span aria-hidden="true">&#x2713;</span> {{ $acesso->nome }}
                            </span>
                        @endforeach
                    </div>

                    <div style="display:flex; align-items:center; gap:8px; padding-top:12px; border-top:1px solid #F3F4F6;">
                        <div class="star-rating" aria-label="Nota {{ $clinica->mediaAvaliacoes() }} de 5 estrelas">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($clinica->mediaAvaliacoes()) ? 'filled' : '' }}" aria-hidden="true">&#x2605;</span>
                            @endfor
                        </div>
                        <span style="font-size:0.875rem; color:#6B7280;">
                            {{ $clinica->mediaAvaliacoes() }} ({{ $clinica->totalAvaliacoes() }})
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div style="margin-top:40px;">
        {{ $clinicas->links() }}
    </div>
@else
    <div class="card" style="padding:64px 32px; text-align:center;">
        <div style="font-size:4rem; margin-bottom:16px; color:#D1D5DB;" aria-hidden="true">&#x1F50D;</div>
        <p style="color:#6B7280; font-size:1.125rem; margin-bottom:16px;">Nenhuma clinica encontrada com os filtros selecionados.</p>
        <a href="{{ route('clinicas.index') }}" class="btn btn-primary">
            Limpar filtros e buscar novamente
        </a>
    </div>
@endif
@endsection
