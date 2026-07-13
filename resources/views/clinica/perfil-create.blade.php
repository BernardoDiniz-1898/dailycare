@extends('layouts.app')

@section('titulo', 'Cadastrar Clinica')

@section('conteudo')
<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:32px;">
    <span aria-hidden="true">&#x1F3E5;</span> Cadastrar Clinica
</h1>

<form method="POST" action="{{ route('clinica.perfil.store') }}" class="card form-section" style="padding:32px;" aria-label="Formulario de cadastro de clinica">
    @csrf

    {{-- Dados da Clinica --}}
    <fieldset>
        <legend style="font-size:1.25rem; font-weight:700; color:#111827; margin-bottom:20px; padding-bottom:8px; border-bottom:2px solid #E5E7EB;">
            <span aria-hidden="true">&#x1F4CB;</span> Dados da Clinica
        </legend>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
            <div class="form-group">
                <label for="razao_social" class="form-label">Razao Social <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}" required class="form-input">
                @error('razao_social') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="nome_fantasia" class="form-label">Nome Fantasia <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}" required class="form-input">
                @error('nome_fantasia') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="cnpj" class="form-label">CNPJ <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required class="form-input" placeholder="00.000.000/0000-00">
                @error('cnpj') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}" class="form-input" placeholder="(00) 00000-0000">
            </div>
            <div class="form-group">
                <label for="email_contato" class="form-label">E-mail de Contato</label>
                <input type="email" id="email_contato" name="email_contato" value="{{ old('email_contato') }}" class="form-input">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label for="descricao" class="form-label">Descricao</label>
                <textarea id="descricao" name="descricao" rows="3" class="form-input">{{ old('descricao') }}</textarea>
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
                <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') }}" required class="form-input" placeholder="Rua, Avenida...">
                @error('logradouro') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="numero" class="form-label">Numero <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="numero" name="numero" value="{{ old('numero') }}" required class="form-input">
                @error('numero') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="complemento" class="form-label">Complemento</label>
                <input type="text" id="complemento" name="complemento" value="{{ old('complemento') }}" class="form-input">
            </div>
            <div class="form-group">
                <label for="bairro" class="form-label">Bairro <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}" required class="form-input">
                @error('bairro') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="cidade" class="form-label">Cidade <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}" required class="form-input">
                @error('cidade') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="estado" class="form-label">Estado (UF) <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="estado" name="estado" value="{{ old('estado') }}" required maxlength="2" class="form-input" placeholder="UF">
                @error('estado') <p class="form-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="cep" class="form-label">CEP <span class="required" aria-label="obrigatorio">*</span></label>
                <input type="text" id="cep" name="cep" value="{{ old('cep') }}" required maxlength="9" class="form-input" placeholder="00000-000">
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
                           {{ in_array($esp->id, old('especialidades', [])) ? 'checked' : '' }}>
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
                           {{ in_array($servico->id, old('servicos_acessibilidade', [])) ? 'checked' : '' }}>
                    <span>{{ $servico->nome }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>

    <div style="display:flex; gap:16px; margin-top:40px; padding-top:24px; border-top:2px solid #E5E7EB;">
        <button type="submit" class="btn btn-primary">
            <span aria-hidden="true">&#x2795;</span> Cadastrar Clinica
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</form>
@endsection
