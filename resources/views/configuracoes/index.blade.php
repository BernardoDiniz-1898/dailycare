@extends('layouts.app')

@section('titulo', 'Configuracoes')

@section('conteudo')
<div style="max-width:640px; margin:0 auto;">
    <h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text); margin-bottom:24px;">
        <i class="bi bi-gear-fill" aria-hidden="true"></i> Configuracoes
    </h1>

    {{-- Acessibilidade --}}
    <section class="card" style="padding:28px; margin-bottom:20px;" aria-labelledby="titulo-acessibilidade">
        <h2 id="titulo-acessibilidade" style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
            <i class="bi bi-universal-access" aria-hidden="true"></i> Acessibilidade
        </h2>

        <div style="margin-bottom:24px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <label for="slider-fonte" class="form-label" style="margin:0;">Tamanho da fonte</label>
                <span id="slider-fonte-valor" style="font-weight:700; color:#009688;">100%</span>
            </div>
            <input type="range" id="slider-fonte" min="70" max="150" step="10" value="100"
                   class="config-slider"
                   aria-label="Tamanho da fonte, de 70 a 150 por cento"
                   oninput="DailyCare.fonte.definir(this.value); document.getElementById('slider-fonte-valor').textContent = this.value + '%';">
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; padding-top:20px; border-top:1px solid #F3F4F6;">
            <div>
                <p style="font-weight:600; color:var(--color-text); margin:0;">Alto contraste</p>
                <p style="font-size:0.875rem; color:var(--color-text-secondary); margin:2px 0 0;">Aumenta o contraste de cores para melhor legibilidade</p>
            </div>
            <label class="config-toggle">
                <input type="checkbox" id="toggle-alto-contraste"
                       onchange="DailyCare.tema.trocar(this.checked ? 'alto-contraste' : 'claro')">
                <span class="config-toggle-slider" aria-hidden="true"></span>
                <span class="sr-only">Ativar alto contraste</span>
            </label>
        </div>
    </section>

    {{-- Alterar senha --}}
    <section class="card" style="padding:28px; margin-bottom:20px;" aria-labelledby="titulo-senha">
        <h2 id="titulo-senha" style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
            <i class="bi bi-shield-lock-fill" aria-hidden="true"></i> Alterar senha
        </h2>

        <form method="POST" action="{{ route('configuracoes.senha') }}">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom:18px;">
                <label for="senha_atual" class="form-label">Senha atual</label>
                <input type="password" id="senha_atual" name="senha_atual" required class="form-input" autocomplete="current-password">
                @error('senha_atual') <p class="form-error" role="alert"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> {{ $message }}</p> @enderror
            </div>

            <div class="form-group" style="margin-bottom:18px;">
                <label for="nova_senha" class="form-label">Nova senha</label>
                <input type="password" id="nova_senha" name="nova_senha" required class="form-input" autocomplete="new-password" placeholder="Minimo 8 caracteres">
                @error('nova_senha') <p class="form-error" role="alert"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> {{ $message }}</p> @enderror
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label for="nova_senha_confirmation" class="form-label">Confirmar nova senha</label>
                <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required class="form-input" autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Alterar senha</button>
        </form>
    </section>

    {{-- Conta --}}
    <section class="card" style="padding:28px;" aria-labelledby="titulo-conta">
        <h2 id="titulo-conta" style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
            <i class="bi bi-person-fill" aria-hidden="true"></i> Conta
        </h2>

        <div style="display:flex; flex-direction:column; gap:12px; font-size:0.9375rem;">
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--color-text-secondary);">Nome</span>
                <span style="font-weight:600; color:var(--color-text);">{{ Auth::user()->nome }}</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--color-text-secondary);">E-mail</span>
                <span style="font-weight:600; color:var(--color-text);">{{ Auth::user()->email }}</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--color-text-secondary);">Tipo de conta</span>
                <span style="font-weight:600; color:var(--color-text); text-transform:capitalize;">{{ Auth::user()->role }}</span>
            </div>
        </div>

        @if (Auth::user()->isClinica())
            <p style="margin-top:20px; padding-top:16px; border-top:1px solid #F3F4F6;">
                <a href="{{ route('clinica.perfil.edit') }}" style="color:#009688; font-weight:600; text-decoration:none;">
                    Editar dados da clinica <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </p>
        @endif
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const escalaAtual = window.DailyCare.fonte.atual();
        document.getElementById('slider-fonte').value = escalaAtual;
        document.getElementById('slider-fonte-valor').textContent = escalaAtual + '%';

        const contrasteAtivo = document.body.classList.contains('alto-contraste');
        document.getElementById('toggle-alto-contraste').checked = contrasteAtivo;
    });
</script>
@endsection
