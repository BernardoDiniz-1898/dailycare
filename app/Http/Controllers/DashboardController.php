<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador do dashboard.
 * Centraliza a lógica para exibir conteúdo diferente de acordo com o tipo de usuário.
 * A semântica é baseada em roteamento interno e em consultas específicas para cada perfil.
 */
class DashboardController extends Controller
{
    /**
     * Direciona o usuário para o painel correto conforme seu papel.
     */
    public function index(Request $request)
    {
        /** @var Usuario $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->admin();
        }

        if ($user->isClinica()) {
            return $this->clinica($request);
        }

        return $this->paciente();
    }

    /**
     * Monta o dashboard do paciente com seus agendamentos.
     */
    private function paciente()
    {
        $agendamentos = Agendamento::where('paciente_id', Auth::id())
            ->with('clinica')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('dashboard.paciente', compact('agendamentos'));
    }

    /**
     * Monta o dashboard da clínica com os agendamentos recebidos.
     * Aplica filtro por status via query string (?status=...) e calcula estatisticas.
     */
    private function clinica(Request $request)
    {
        $clinica = Auth::user()->clinica;

        if (! $clinica) {
            return view('dashboard.clinica-setup');
        }

        $agendamentos = Agendamento::where('clinica_id', $clinica->id)
            ->with('paciente')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        $statusValidos = ['solicitado', 'confirmado', 'concluido', 'recusado', 'cancelado'];
        $statusAtivo = $request->query('status');

        if (! in_array($statusAtivo, $statusValidos, true)) {
            $statusAtivo = null;
        }

        $agendamentosFiltrados = $statusAtivo
            ? $agendamentos->where('status', $statusAtivo)
            : $agendamentos;

        $contagem = [
            'todos' => $agendamentos->count(),
            'solicitado' => $agendamentos->where('status', 'solicitado')->count(),
            'confirmado' => $agendamentos->where('status', 'confirmado')->count(),
            'concluido' => $agendamentos->where('status', 'concluido')->count(),
            'recusado' => $agendamentos->where('status', 'recusado')->count(),
            'cancelado' => $agendamentos->where('status', 'cancelado')->count(),
        ];

        $receitaEstimada = ($contagem['confirmado'] + $contagem['concluido'])
            * (float) ($clinica->preco_sessao ?? 0);

        return view('dashboard.clinica', compact(
            'clinica',
            'agendamentosFiltrados',
            'statusAtivo',
            'contagem',
            'receitaEstimada'
        ));
    }

    /**
     * Monta o painel administrativo com estatísticas e últimos agendamentos.
     */
    private function admin()
    {
        $clinicasPendentes = Clinica::where('status', 'pendente')->count();
        $clinicasAprovadas = Clinica::where('status', 'aprovada')->count();
        $totalAgendamentos = Agendamento::count();

        $ultimosAgendamentos = Agendamento::with(['paciente', 'clinica'])
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'clinicasPendentes',
            'clinicasAprovadas',
            'totalAgendamentos',
            'ultimosAgendamentos'
        ));
    }
}
