<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->admin();
        }

        if ($user->isClinica()) {
            return $this->clinica();
        }

        return $this->paciente();
    }

    private function paciente()
    {
        $agendamentos = Agendamento::where('paciente_id', Auth::id())
            ->with('clinica')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('dashboard.paciente', compact('agendamentos'));
    }

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
