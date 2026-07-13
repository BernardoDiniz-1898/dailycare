@extends('layouts.app')

@section('titulo', 'Servicos de Acessibilidade')

@section('conteudo')
<a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:8px; color:#1A56DB; font-weight:600; margin-bottom:24px; text-decoration:none;">
    <span aria-hidden="true">&#x2190;</span> Voltar ao Painel
</a>

<h1 style="font-size:1.75rem; font-weight:800; color:#111827; margin-bottom:32px;">
    <span aria-hidden="true">&#x2713;</span> Gerenciar Servicos de Acessibilidade
</h1>

{{-- Formulario de adicao --}}
<section class="card" style="padding:28px; margin-bottom:32px;" aria-label="Adicionar novo servico de acessibilidade">
    <h2 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:16px;">Adicionar Servico</h2>
    <form method="POST" action="{{ route('admin.servicos-acessibilidade.store') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        @csrf
        <div class="form-group" style="flex:1; min-width:300px;">
            <label for="nome" class="form-label">Nome do servico <span class="required" aria-label="obrigatorio">*</span></label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: Rampa de Acesso" class="form-input">
            @error('nome') <p class="form-error" role="alert">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn btn-primary">
            <span aria-hidden="true">&#x2795;</span> Adicionar
        </button>
    </form>
</section>

{{-- Lista --}}
<section aria-label="Lista de servicos de acessibilidade">
    <div class="table-container">
        <table class="data-table" role="table">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col" style="text-align:right;">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($servicos as $servico)
                    <tr>
                        <td style="font-weight:600;">
                            <span aria-hidden="true" style="color:#047857;">&#x2713;</span> {{ $servico->nome }}
                        </td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.servicos-acessibilidade.destroy', $servico) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Remover este servico?')">
                                    Remover
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:#6B7280; padding:40px;">
                            Nenhum servico de acessibilidade cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
