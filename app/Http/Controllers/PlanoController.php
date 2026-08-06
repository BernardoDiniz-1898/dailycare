<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    public function index()
    {
        $clinica = Auth::user()->clinica;
        abort_unless($clinica, 403);

        $planos = Plano::orderBy('preco_mensal')->get();
        $assinaturaAtiva = $clinica->assinaturaAtiva()->with('plano')->first();

        return view('planos.index', compact('planos', 'assinaturaAtiva'));
    }

    // Simula a contratacao do plano (sem gateway de pagamento real por enquanto)
    public function assinar(Request $request, Plano $plano)
    {
        $clinica = Auth::user()->clinica;
        abort_unless($clinica, 403);

        $validado = $request->validate([
            'periodo' => 'required|in:mensal,anual',
        ]);

        // Cancela assinatura anterior, se existir, antes de criar a nova
        $clinica->assinaturas()->where('status', 'ativa')->update(['status' => 'cancelada']);

        $dataFim = $validado['periodo'] === 'anual'
            ? now()->addYear()
            : now()->addMonth();

        Assinatura::create([
            'clinica_id' => $clinica->id,
            'plano_id' => $plano->id,
            'periodo' => $validado['periodo'],
            'status' => 'ativa',
            'data_inicio' => now(),
            'data_fim' => $dataFim,
        ]);

        return redirect()->route('planos.index')->with('success', 'Plano ' . $plano->nome . ' ativado com sucesso!');
    }

    public function cancelar()
    {
        $clinica = Auth::user()->clinica;
        abort_unless($clinica, 403);

        $clinica->assinaturas()->where('status', 'ativa')->update(['status' => 'cancelada']);

        return redirect()->route('planos.index')->with('success', 'Assinatura cancelada.');
    }
}
