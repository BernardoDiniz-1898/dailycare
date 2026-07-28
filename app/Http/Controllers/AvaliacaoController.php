<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinica_id' => 'required|exists:clinicas,id',
            'nota' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $clinica = \App\Models\Clinica::find($validated['clinica_id']);
        if (!$clinica || $clinica->status !== 'aprovada') {
            return back()->withErrors(['clinica_id' => 'Esta clinica nao esta disponivel para avaliacao.']);
        }

        // Só deixa avaliar quem realmente já foi atendido pela clinica (evita avaliacao sem ter usado o servico)
        $atendimentoConcluido = Agendamento::where('paciente_id', Auth::id())
            ->where('clinica_id', $validated['clinica_id'])
            ->where('status', 'concluido')
            ->exists();

        if (!$atendimentoConcluido) {
            return back()->withErrors(['nota' => 'Voce so pode avaliar clinicas onde ja teve um atendimento concluido.']);
        }

        $existe = Avaliacao::where('paciente_id', Auth::id())
            ->where('clinica_id', $validated['clinica_id'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['nota' => 'Voce ja avaliou esta clinica.']);
        }

        Avaliacao::create([
            'paciente_id' => Auth::id(),
            'clinica_id' => $validated['clinica_id'],
            'nota' => $validated['nota'],
            'comentario' => $validated['comentario'] ?? null,
        ]);

        return back()->with('success', 'Avaliacao enviada com sucesso!');
    }
}
