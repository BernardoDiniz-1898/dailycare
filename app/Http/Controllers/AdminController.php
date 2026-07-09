<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function clinicasPendentes()
    {
        $clinicas = Clinica::where('status', 'pendente')
            ->with(['usuario', 'especialidades'])
            ->latest()
            ->get();

        return view('admin.clinicas-pendentes', compact('clinicas'));
    }

    public function aprovarClinica(Clinica $clinica)
    {
        $clinica->update(['status' => 'aprovada']);
        return back()->with('success', "Clinica '{$clinica->nome_fantasia}' aprovada com sucesso!");
    }

    public function rejeitarClinica(Clinica $clinica)
    {
        $clinica->update(['status' => 'rejeitada']);
        return back()->with('success', "Clinica '{$clinica->nome_fantasia}' rejeitada.");
    }

    public function especialidades()
    {
        $especialidades = Especialidade::latest()->get();
        return view('admin.especialidades', compact('especialidades'));
    }

    public function storeEspecialidade(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:especialidades,nome',
            'descricao' => 'nullable|string|max:500',
        ]);

        Especialidade::create($validated);

        return back()->with('success', 'Especialidade criada com sucesso!');
    }

    public function destroyEspecialidade(Especialidade $especialidade)
    {
        $especialidade->delete();
        return back()->with('success', 'Especialidade removida.');
    }

    public function servicosAcessibilidade()
    {
        $servicos = ServicoAcessibilidade::latest()->get();
        return view('admin.servicos-acessibilidade', compact('servicos'));
    }

    public function storeServicoAcessibilidade(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:servicos_acessibilidade,nome',
        ]);

        ServicoAcessibilidade::create($validated);

        return back()->with('success', 'Servico de acessibilidade criado com sucesso!');
    }

    public function destroyServicoAcessibilidade(ServicoAcessibilidade $servico)
    {
        $servico->delete();
        return back()->with('success', 'Servico removido.');
    }
}
