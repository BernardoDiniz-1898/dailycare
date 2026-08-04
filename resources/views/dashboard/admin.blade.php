@extends('layouts.app')

@section('titulo', 'Painel Administrativo')

@section('conteudo')
<h1 style="font-size:1.75rem; font-weight:800; color:var(--color-text); margin-bottom:32px;">
    <i class="bi bi-gear-fill" aria-hidden="true"></i> Painel Administrativo
</h1>

{{-- Estatisticas --}}
<section aria-label="Resumo do sistema" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:32px;">
    <a href="{{ route('admin.clinicas-pendentes') }}" class="card" style="padding:24px; text-decoration:none; display:block;">
        <p style="font-size:2.5rem; font-weight:800; color:var(--color-warning);"> {{ $clinicasPendentes }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Clinicas Pendentes</p>
    </a>
    <div class="card" style="padding:24px;">
        <p style="font-size:2.5rem; font-weight:800; color:var(--color-success);">{{ $clinicasAprovadas }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Clinicas Aprovadas</p>
    </div>
    <div class="card" style="padding:24px;">
        <p style="font-size:2.5rem; font-weight:800; color:var(--color-primary);">{{ $totalAgendamentos }}</p>
        <p style="color:var(--color-text-secondary); font-weight:600;">Total de Agendamentos</p>
    </div>
</section>

{{-- Acoes --}}
<section aria-label="Acoes administrativas" style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:40px;">
    <a href="{{ route('admin.clinicas-pendentes') }}" class="btn btn-primary">
        <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Aprovar Clinicas
    </a>
    <a href="{{ route('admin.especialidades') }}" class="btn btn-secondary">
        <i class="bi bi-clipboard-data" aria-hidden="true"></i> Especialidades
    </a>
    <a href="{{ route('admin.servicos-acessibilidade') }}" class="btn btn-secondary">
        <i class="bi bi-check-lg" aria-hidden="true"></i> Servicos de Acessibilidade
    </a>
</section>

{{-- Ultimos agendamentos --}}
<section aria-label="Ultimos agendamentos">
    <h2 style="font-size:1.5rem; font-weight:700; color:var(--color-text); margin-bottom:20px;">
        <i class="bi bi-calendar-event" aria-hidden="true"></i> Ultimos Agendamentos
    </h2>

    @if ($ultimosAgendamentos->count() > 0)
        <div class="table-container">
            <table class="data-table" role="table">
                <thead>
                    <tr>
                        <th scope="col">Paciente</th>
                        <th scope="col">Clinica</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Preco</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ultimosAgendamentos as $agendamento)
                        <tr>
                            <td style="font-weight:600;">{{ $agendamento->paciente->nome }}</td>
                            <td>{{ $agendamento->clinica->nome_fantasia }}</td>
                            <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                            <td>{{ substr($agendamento->hora, 0, 5) }}</td>
                            <td>
                                @if ($agendamento->clinica->preco_sessao)
                                    R$ {{ number_format($agendamento->clinica->preco_sessao, 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $agendamento->statusCor() }}">{{ $agendamento->statusLabel() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card" style="padding:64px 32px; text-align:center;">
            <p style="color:var(--color-text-secondary); font-size:1.125rem;">Nenhum agendamento registrado.</p>
        </div>
    @endif
</section>
@endsection
