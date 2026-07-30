<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Controlador administrativo.
 * Este arquivo concentra as ações de gestão do painel de administração,
 * como aprovação de clínicas e cadastro de especialidades/serviços.
 * A sintaxe usa classes e métodos públicos do Laravel para responder a requisições.
 */
class AdminController extends Controller
{
    /**
     * Lista clínicas com status pendente.
     * A consulta busca registros no banco usando o modelo Clinica e carrega relações.
     */
    public function clinicasPendentes()
    {
        $clinicas = Clinica::where('status', 'pendente')
            ->with(['usuario', 'especialidades'])
            ->latest()
            ->get();

        return view('admin.clinicas-pendentes', compact('clinicas'));
    }

    /**
     * Aprova uma clínica recebida como parâmetro.
     * O método atualiza o campo status e retorna um feedback para a view anterior.
     */
    public function aprovarClinica(Clinica $clinica)
    {
        $clinica->update(['status' => 'aprovada']);
        return back()->with('success', "Clinica '{$clinica->nome_fantasia}' aprovada com sucesso!");
    }

    /**
     * Rejeita uma clínica alterando o status para rejeitada.
     */
    public function rejeitarClinica(Clinica $clinica)
    {
        $clinica->update(['status' => 'rejeitada']);
        return back()->with('success', "Clinica '{$clinica->nome_fantasia}' rejeitada.");
    }

    /**
     * Exibe a página de listagem de especialidades.
     */
    public function especialidades()
    {
        $especialidades = Especialidade::latest()->get();
        return view('admin.especialidades', compact('especialidades'));
    }

    /**
     * Cria uma nova especialidade a partir dos dados validados do formulário.
     * A validação evita dados inválidos e duplicidades no banco.
     */
    public function storeEspecialidade(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:especialidades,nome',
            'descricao' => 'nullable|string|max:500',
        ]);

        Especialidade::create($validated);

        return back()->with('success', 'Especialidade criada com sucesso!');
    }

    /**
     * Remove uma especialidade recebida por model binding.
     */
    public function destroyEspecialidade(Especialidade $especialidade)
    {
        $especialidade->delete();
        return back()->with('success', 'Especialidade removida.');
    }

    /**
     * Exibe a tela para gerenciar serviços de acessibilidade.
     */
    public function servicosAcessibilidade()
    {
        $servicos = ServicoAcessibilidade::latest()->get();
        return view('admin.servicos-acessibilidade', compact('servicos'));
    }

    /**
     * Cria um novo serviço de acessibilidade após validar os dados recebidos.
     */
    public function storeServicoAcessibilidade(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:servicos_acessibilidade,nome',
        ]);

        ServicoAcessibilidade::create($validated);

        return back()->with('success', 'Servico de acessibilidade criado com sucesso!');
    }

    /**
     * Exclui um serviço de acessibilidade do banco.
     */
    public function destroyServicoAcessibilidade(ServicoAcessibilidade $servico)
    {
        $servico->delete();
        return back()->with('success', 'Servico removido.');
    }
}
