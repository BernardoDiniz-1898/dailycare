@extends('layouts.app')

@section('titulo', 'Gerenciar Especialidades')

@section('conteudo')
<a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#009688; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <span aria-hidden="true">&#x2190;</span> Voltar ao Painel
</a>

<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:32px;">
    <span aria-hidden="true">&#x1F4CB;</span> Gerenciar Especialidades
</h1>

{{-- Formulario de adicao --}}
<section class="card" style="padding:28px; margin-bottom:32px;" aria-label="Adicionar nova especialidade">
    <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:16px;">Adicionar Especialidade</h2>
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
            <span aria-hidden="true">&#x2795;</span> Adicionar
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
                        <td style="color:#6B7280;">{{ $especialidade->descricao ?: '-' }}</td>
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
                        <td colspan="3" style="text-align:center; color:#6B7280; padding:40px;">
                            Nenhuma especialidade cadastrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
