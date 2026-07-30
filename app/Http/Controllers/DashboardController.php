<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
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
    public function index()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->admin();
        }

        if ($user->isClinica()) {
            return $this->clinica();
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
     */
    private function clinica()
    {
        $clinica = Auth::user()->clinica;

        if (!$clinica) {
            return view('dashboard.clinica-setup');
        }

        $agendamentos = Agendamento::where('clinica_id', $clinica->id)
            ->with('paciente')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        $pendentes = $agendamentos->where('status', 'solicitado')->count();
        $confirmados = $agendamentos->where('status', 'confirmado')->count();

        return view('dashboard.clinica', compact('clinica', 'agendamentos', 'pendentes', 'confirmados'));
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
            ->latest()
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
