<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\HorarioDisponivel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pelo ciclo de agendamentos.
 * Aqui são definidas as ações de criação, alteração e cancelamento de agendamentos.
 * A semântica do código está ligada ao fluxo de autorização e validação de dados.
 */
class AgendamentoController extends Controller
{
    /**
     * Cria um novo agendamento após validar os dados enviados pelo formulário.
     * O método verifica se o horário já está ocupado antes de persistir o registro.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinica_id' => 'required|exists:clinicas,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'observacao_paciente' => 'nullable|string|max:1000',
        ]);

        $existe = Agendamento::where('clinica_id', $validated['clinica_id'])
            ->where('data', $validated['data'])
            ->where('hora', $validated['hora'])
            ->whereNotIn('status', ['cancelado', 'recusado'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['hora' => 'Este horario ja esta ocupado.'])->withInput();
        }

        Agendamento::create([
            'paciente_id' => Auth::id(),
            'clinica_id' => $validated['clinica_id'],
            'data' => $validated['data'],
            'hora' => $validated['hora'],
            'observacao_paciente' => $validated['observacao_paciente'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Agendamento solicitado com sucesso!');
    }

    /**
     * Atualiza o status de um agendamento.
     * A lógica verifica se o usuário autenticado tem permissão para alterar o registro.
     */
    public function update(Request $request, Agendamento $agendamento)
    {

        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->isClinica()) {
            $clinica = $user->clinica;
            if (!$clinica || $agendamento->clinica_id != $clinica->id) {
                abort(403);
            }
        } elseif ($agendamento->paciente_id != $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmado,recusado,cancelado,concluido',
            'observacao_clinica' => 'nullable|string|max:1000',
        ]);

        $agendamento->update($validated);

        return back()->with('success', 'Agendamento atualizado com sucesso!');
    }

    /**
     * Cancela um agendamento existente.
     * O cancelamento é tratado como uma atualização de status e não como exclusão física.
     */
    public function destroy(Agendamento $agendamento)
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->isClinica()) {
            $clinica = $user->clinica;
            if (!$clinica || $agendamento->clinica_id != $clinica->id) {
                abort(403);
            }
        } elseif ($agendamento->paciente_id != $user->id) {
            abort(403);
        }

        $agendamento->update(['status' => 'cancelado']);

        return back()->with('success', 'Agendamento cancelado.');
    }
}
