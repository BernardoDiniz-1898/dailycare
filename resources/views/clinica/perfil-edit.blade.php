@extends('layouts.app')

@section('titulo', 'Editar Clinica')

@section('conteudo')
<a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <span aria-hidden="true">&#x2190;</span> Voltar ao Painel
</a>

<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:32px;">
    <span aria-hidden="true">&#x270F;</span> Editar Perfil da Clinica
</h1>

<form method="POST" action="{{ route('clinica.perfil.update') }}" class="card form-section" style="padding:32px;" aria-label="Formulario de edicao de clinica">
    @csrf
    @method('PUT')

    {{-- Dados da Clinica --}}
    <fieldset>
        <legend style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px; padding-bottom:8px; border-bottom:2px solid #E5E7EB;">
            <span aria-hidden="true">&#x1F4CB;</span> Dados da Clinica
        </legend>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
            <div class="form-group">
                <label for="razao_social" class="form-label">Razao Social <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $clinica->razao_social) }}" required class="form-input">
                @error('razao_social') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="nome_fantasia" class="form-label">Nome Fantasia <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $clinica->nome_fantasia) }}" required class="form-input">
                @error('nome_fantasia') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $clinica->telefone) }}" class="form-input">
            </div>
            <div class="form-group">
                <label for="email_contato" class="form-label">E-mail de Contato</label>
                <input type="email" id="email_contato" name="email_contato" value="{{ old('email_contato', $clinica->email_contato) }}" class="form-input">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label for="descricao" class="form-label">Descricao</label>
                <textarea id="descricao" name="descricao" rows="3" class="form-input">{{ old('descricao', $clinica->descricao) }}</textarea>
            </div>
        </div>
    </fieldset>

    {{-- Endereco --}}
    <fieldset style="margin-top:32px;">
        <legend style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px; padding-bottom:8px; border-bottom:2px solid #E5E7EB;">
            <span aria-hidden="true">&#x1F4CD;</span> Endereco
        </legend>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
            <div class="form-group" style="grid-column:span 2;">
                <label for="logradouro" class="form-label">Logradouro <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $clinica->logradouro) }}" required class="form-input">
                @error('logradouro') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="numero" class="form-label">Numero <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="numero" name="numero" value="{{ old('numero', $clinica->numero) }}" required class="form-input">
                @error('numero') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="complemento" class="form-label">Complemento</label>
                <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $clinica->complemento) }}" class="form-input">
            </div>
            <div class="form-group">
                <label for="bairro" class="form-label">Bairro <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $clinica->bairro) }}" required class="form-input">
                @error('bairro') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="cidade" class="form-label">Cidade <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $clinica->cidade) }}" required class="form-input">
                @error('cidade') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="estado" class="form-label">Estado (UF) <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="estado" name="estado" value="{{ old('estado', $clinica->estado) }}" required maxlength="2" class="form-input" placeholder="UF">
                @error('estado') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="cep" class="form-label">CEP <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="cep" name="cep" value="{{ old('cep', $clinica->cep) }}" required maxlength="9" class="form-input" placeholder="00000-000">
                @error('cep') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    {{-- Especialidades --}}
    <fieldset style="margin-top:32px;">
        <legend style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px; padding-bottom:8px; border-bottom:2px solid #E5E7EB;">
            <span aria-hidden="true">&#x267E;</span> Especialidades Oferecidas <span class="required" aria-label="obrigatorio">*</span>
        </legend>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:12px;">
            @foreach ($especialidades as $esp)
                <label class="checkbox-label">
                    <input type="checkbox" name="especialidades[]" value="{{ $esp->id }}"
                           {{ in_array($esp->id, old('especialidades', $clinica->especialidades->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <span>{{ $esp->nome }}</span>
                </label>
            @endforeach
        </div>
        @error('especialidades') <p class="form-error" role="alert" style="margin-top:12px;">{{ $message }}</p> @enderror
    </fieldset>

    {{-- Acessibilidade --}}
    <fieldset style="margin-top:32px;">
        <legend style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px; padding-bottom:8px; border-bottom:2px solid #E5E7EB;">
            <span aria-hidden="true">&#x2713;</span> Recursos de Acessibilidade
        </legend>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:12px;">
            @foreach ($servicos as $servico)
                <label class="checkbox-label">
                    <input type="checkbox" name="servicos_acessibilidade[]" value="{{ $servico->id }}"
                           {{ in_array($servico->id, old('servicos_acessibilidade', $clinica->servicosAcessibilidade->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <span>{{ $servico->nome }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>

    <div style="display:flex; gap:16px; margin-top:40px; padding-top:24px; border-top:2px solid #E5E7EB;">
        <button type="submit" class="btn btn-primary">
            <span aria-hidden="true">&#x1F4BE;</span> Salvar Alteracoes
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</form>
@endsection
