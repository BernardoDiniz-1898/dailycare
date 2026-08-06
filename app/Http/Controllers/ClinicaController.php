<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use Illuminate\Http\Request;

/**
 * Controlador de clínicas.
 * Sua função é listar clínicas aprovadas e exibir o detalhamento de cada perfil.
 * O uso de consultas com filtros mostra a semântica de busca e relacionamento no Eloquent.
 */
class ClinicaController extends Controller
{
    /**
     * Lista clínicas aprovadas aplicando filtros recebidos na requisição.
     * Os métodos whereHas e where permitem montar consultas mais específicas.
     */
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

        $query->withCount('avaliacoes')
            ->withAvg('avaliacoes', 'nota')
            ->withCount(['agendamentos as atendimentos_concluidos_count' => function ($q) {
                $q->where('status', 'concluido');
            }]);

        // Clinicas com plano de prioridade ativo aparecem primeiro na busca
        $query->addSelect([
            'tem_prioridade' => \App\Models\Assinatura::query()
                ->join('planos', 'planos.id', '=', 'assinaturas.plano_id')
                ->whereColumn('assinaturas.clinica_id', 'clinicas.id')
                ->where('assinaturas.status', 'ativa')
                ->where('assinaturas.data_fim', '>=', now())
                ->where('planos.prioridade_busca', true)
                ->selectRaw('1')
                ->limit(1),
        ])->orderByDesc('tem_prioridade');

        switch ($request->input('ordenar')) {
            case 'avaliacao':
                $query->orderByDesc('avaliacoes_avg_nota');
                break;
            case 'avaliacoes_count':
                $query->orderByDesc('avaliacoes_count');
                break;
            default:
                $query->latest();
                break;
        }

        $clinicas = $query->paginate(12)->withQueryString();
        $especialidades = Especialidade::where('ativa', true)->get();
        $servicos = ServicoAcessibilidade::where('ativa', true)->get();

        return view('clinicas.index', compact('clinicas', 'especialidades', 'servicos'));
    }

    /**
     * Exibe o detalhe de uma clínica aprovada.
     * O método carrega relações para mostrar informações completas na view.
     */
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

        $clinica->loadCount([
            'agendamentos as atendimentos_concluidos_count' => fn ($q) => $q->where('status', 'concluido'),
        ]);

        return view('clinicas.show', compact('clinica'));
    }
}
