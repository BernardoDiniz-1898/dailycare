<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\Mensagem;
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
            return $this->clinica();
        }

        return $this->paciente();
    }

    /**
     * Monta o dashboard do paciente com boas-vindas, proximas consultas e recomendados.
     */
    private function paciente()
    {
        $user = Auth::user();

        $agendamentos = Agendamento::where('paciente_id', Auth::id())
            ->with('clinica')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        $proximosAgendamentos = Agendamento::where('paciente_id', Auth::id())
            ->whereDate('data', '>=', today())
            ->whereIn('status', ['solicitado', 'confirmado'])
            ->with('clinica')
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->take(5)
            ->get();

        $agendamentosPendentes = $agendamentos->where('status', 'solicitado')->count();

        $recomendados = Clinica::where('status', 'aprovada')
            ->where('ativa', true)
            ->with(['especialidades', 'avaliacoes'])
            ->get()
            ->sortByDesc(fn (Clinica $c) => [$c->mediaAvaliacoes(), $c->totalAvaliacoes()])
            ->take(3)
            ->values();

        return view('dashboard.paciente', compact(
            'user',
            'agendamentos',
            'proximosAgendamentos',
            'agendamentosPendentes',
            'recomendados'
        ));
    }

    /**
     * Monta o dashboard da clínica com métricas, prévia da agenda do dia e avaliações.
     */
    private function clinica()
    {
        $user = Auth::user();
        $clinica = $user->clinica;

        if (! $clinica) {
            return view('dashboard.clinica-setup');
        }

        $agendamentos = Agendamento::where('clinica_id', $clinica->id)
            ->with('paciente')
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

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

        $ativos = $agendamentos->whereNotIn('status', ['cancelado', 'recusado']);

        $sessoesHoje = $ativos->filter(function (Agendamento $a) {
            return $a->data->isSameDay(today());
        })->count();

        $pacientesAtivos = $ativos->pluck('paciente_id')->unique()->count();

        $avaliacaoMedia = $clinica->mediaAvaliacoes();
        $totalAvaliacoes = $clinica->totalAvaliacoes();

        $ultimasAvaliacoes = $clinica->avaliacoes()
            ->with('paciente')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $naoLidas = Mensagem::where('lida', false)
            ->where('remetente_id', '!=', $user->id)
            ->whereHas('conversa', fn ($query) => $query->where('clinica_id', $clinica->id))
            ->count();

        $perfilIncompleto = empty($clinica->foto_capa)
            || empty($clinica->descricao)
            || $clinica->especialidades()->count() === 0;

        $agendaHoje = $ativos->filter(function (Agendamento $a) {
            return $a->data->isSameDay(today());
        })->sortBy('hora')->values();

        $diasPT = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
        $diaSemanaHoje = $diasPT[today()->dayOfWeek];

        $horariosHoje = $clinica->horarios()
            ->where('dia_semana', $diaSemanaHoje)
            ->where('ativo', true)
            ->get();

        $slotsHoje = collect();
        foreach ($horariosHoje as $horario) {
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
            $fim = \Carbon\Carbon::parse($horario->hora_fim);
            $cursor = $inicio->copy();
            while ($cursor->lt($fim)) {
                $hora = $cursor->format('H:i');
                $slotsHoje->push([
                    'hora' => $hora,
                    'agendamento' => $agendaHoje->firstWhere('hora', $hora),
                ]);
                $cursor->addMinutes(60);
            }
        }

        if ($slotsHoje->isEmpty() && $agendaHoje->isNotEmpty()) {
            $slotsHoje = $agendaHoje->map(fn (Agendamento $a) => [
                'hora' => substr($a->hora, 0, 5),
                'agendamento' => $a,
            ])->sortBy('hora')->values();
        }

        return view('dashboard.clinica', compact(
            'user',
            'clinica',
            'agendamentos',
            'contagem',
            'receitaEstimada',
            'sessoesHoje',
            'pacientesAtivos',
            'avaliacaoMedia',
            'totalAvaliacoes',
            'ultimasAvaliacoes',
            'naoLidas',
            'perfilIncompleto',
            'agendaHoje',
            'slotsHoje'
        ));
    }

    /**
     * Página dedicada da agenda da clínica: navegação por dia, linha do tempo
     * de horários e lista de agendamentos com filtro por status.
     */
    public function agenda(Request $request)
    {
        $user = Auth::user();
        $clinica = $user->clinica;

        if (! $clinica) {
            return redirect()->route('clinica.perfil.create');
        }

        $data = today();

        if ($request->query('data')) {
            try {
                $data = \Carbon\Carbon::parse($request->query('data'));
            } catch (\Throwable $e) {
                $data = today();
            }
        }

        $statusValidos = ['solicitado', 'confirmado', 'concluido', 'recusado', 'cancelado'];
        $statusAtivo = $request->query('status');

        if (! in_array($statusAtivo, $statusValidos, true)) {
            $statusAtivo = null;
        }

        $diasPT = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
        $diaSemana = $diasPT[$data->dayOfWeek];

        $horariosDia = $clinica->horarios()
            ->where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->get();

        $agendamentosDia = Agendamento::where('clinica_id', $clinica->id)
            ->whereDate('data', $data->toDateString())
            ->with('paciente')
            ->orderBy('hora', 'asc')
            ->get();

        $contagem = [
            'todos' => $agendamentosDia->count(),
            'solicitado' => $agendamentosDia->where('status', 'solicitado')->count(),
            'confirmado' => $agendamentosDia->where('status', 'confirmado')->count(),
            'concluido' => $agendamentosDia->where('status', 'concluido')->count(),
            'recusado' => $agendamentosDia->where('status', 'recusado')->count(),
            'cancelado' => $agendamentosDia->where('status', 'cancelado')->count(),
        ];

        $agendamentosFiltrados = $statusAtivo
            ? $agendamentosDia->where('status', $statusAtivo)->values()
            : $agendamentosDia;

        $slots = collect();
        foreach ($horariosDia as $horario) {
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
            $fim = \Carbon\Carbon::parse($horario->hora_fim);
            $cursor = $inicio->copy();
            while ($cursor->lt($fim)) {
                $hora = $cursor->format('H:i');
                $slots->push([
                    'hora' => $hora,
                    'agendamento' => $agendamentosDia->firstWhere('hora', $hora),
                ]);
                $cursor->addMinutes(60);
            }
        }

        if ($slots->isEmpty() && $agendamentosDia->isNotEmpty()) {
            $slots = $agendamentosDia
                ->whereNotIn('status', ['cancelado', 'recusado'])
                ->values()
                ->map(fn (Agendamento $a) => [
                    'hora' => substr($a->hora, 0, 5),
                    'agendamento' => $a,
                ])
                ->sortBy('hora')
                ->values();
        }

        $anterior = $data->copy()->subDay();
        $proxima = $data->copy()->addDay();

        return view('dashboard.agenda', compact(
            'user',
            'clinica',
            'data',
            'diaSemana',
            'horariosDia',
            'agendamentosDia',
            'agendamentosFiltrados',
            'statusAtivo',
            'contagem',
            'slots',
            'anterior',
            'proxima'
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
