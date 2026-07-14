<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use Illuminate\Http\Request;

class ClinicaController extends Controller
{
    public function index(Request $request)
    {
        $query = Clinica::aprovadas()->with(['especialidades', 'servicosAcessibilidade']);

        if ($request->filled('especialidade')) {
            $query->whereHas('especialidades', function ($q) use ($request) {
                $q->where('especialidades.id', $request->especialidade);
            });
        }

        if ($request->filled('cidade')) {
            $query->where('cidade', 'like', "%{$request->cidade}%");
        }

        if ($request->filled('cep')) {
            $query->where('cep', 'like', "%{$request->cep}%");
        }

        if ($request->filled('acessibilidade')) {
            $query->whereHas('servicosAcessibilidade', function ($q) use ($request) {
                $q->where('servicos_acessibilidade.id', $request->acessibilidade);
            });
        }

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome_fantasia', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%")
                  ->orWhere('bairro', 'like', "%{$busca}%")
                  ->orWhere('cidade', 'like', "%{$busca}%");
            });
        }

        $clinicas = $query->latest()->paginate(12)->withQueryString();
        $especialidades = Especialidade::where('ativa', true)->get();
        $servicos = ServicoAcessibilidade::where('ativa', true)->get();

        return view('clinicas.index', compact('clinicas', 'especialidades', 'servicos'));
    }

    public function show(Clinica $clinica)
    {
        if ($clinica->status !== 'aprovada') {
            abort(404);
        }

        $clinica->load([
            'especialidades',
            'servicosAcessibilidade',
            'fotos',
            'horarios',
            'avaliacoes' => fn ($q) => $q->where('visivel', true),
            'avaliacoes.paciente',
        ]);

        return view('clinicas.show', compact('clinica'));
    }
}
