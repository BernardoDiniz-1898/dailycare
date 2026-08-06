@extends('layouts.app')

@section('titulo', 'Gerenciar Especialidades')

@section('conteudo')
<a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar ao Painel
</a>

<h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text); margin-bottom:32px;">
    <i class="bi bi-clipboard-data" aria-hidden="true"></i> Gerenciar Especialidades
</h1>

{{-- Formulario de adicao --}}
<section class="card" style="padding:28px; margin-bottom:32px;" aria-label="Adicionar nova especialidade">
    <h2 style="font-size:1.125rem; font-weight:700; color:var(--color-text); margin-bottom:16px;">Adicionar Especialidade</h2>
    <form method="POST" action="{{ route('admin.especialidades.store') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        @csrf
        <div class="form-group" style="flex:1; min-width:200px;">
            <label for="nome" class="form-label">Nome <span class="required" aria-label="obrigatorio">*</span></label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: Fisioterapia Neurofuncional" class="form-input">
            @error('nome') <p class="form-error" role="alert">{{ $message }}</p> @enderror
        </div>
        <div class="form-group" style="flex:1; min-width:200px;">
            <label for="descricao" class="form-label">Descricao</label>
            <input type="text" id="descricao" name="descricao" placeholder="Descricao opcional" class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Adicionar
        </button>
    </form>
</section>

{{-- Lista --}}
<section aria-label="Lista de especialidades">
    <div class="table-container">
        <table class="data-table" role="table">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Descricao</th>
                    <th scope="col" style="text-align:right;">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($especialidades as $especialidade)
                    <tr>
                        <td style="font-weight:600;">{{ $especialidade->nome }}</td>
                        <td style="color:var(--color-text-secondary);">{{ $especialidade->descricao ?: '-' }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.especialidades.destroy', $especialidade) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Remover esta especialidade?')">
                                    Remover
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; color:var(--color-text-secondary); padding:40px;">
                            Nenhuma especialidade cadastrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
