<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\HorarioDisponivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
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

    public function update(Request $request, Agendamento $agendamento)
    {
        $user = Auth::user();

        if ($user->isClinica()) {
            $clinica = $user->clinica;
            if (!$clinica || $agendamento->clinica_id !== $clinica->id) {
                abort(403);
            }
        } elseif ($agendamento->paciente_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmado,recusado,cancelado,concluido',
            'observacao_clinica' => 'nullable|string|max:1000',
        ]);

        $agendamento->update($validated);

        return back()->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(Agendamento $agendamento)
    {
        $user = Auth::user();

        if ($user->isClinica()) {
            $clinica = $user->clinica;
            if (!$clinica || $agendamento->clinica_id !== $clinica->id) {
                abort(403);
            }
        } elseif ($agendamento->paciente_id !== $user->id) {
            abort(403);
        }

        $agendamento->update(['status' => 'cancelado']);

        return back()->with('success', 'Agendamento cancelado.');
    }
}
