<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de perfil da clínica.
 * Responsável por criar e editar os dados cadastrais da clínica.
 * Os métodos usam validação de formulário e relacionamento many-to-many com especialidades e serviços.
 */
class ClinicaPerfilController extends Controller
{
    /**
     * Abre a tela de edição do perfil da clínica do usuário autenticado.
     */
    public function edit()
    {
        $clinica = Auth::user()->clinica;

        if (!$clinica) {
            return redirect()->route('clinica.perfil.create');
        }

        $especialidades = Especialidade::where('ativa', true)->get();
        $servicos = ServicoAcessibilidade::where('ativa', true)->get();

        return view('clinica.perfil-edit', compact('clinica', 'especialidades', 'servicos'));
    }

    /**
     * Exibe o formulário de criação de perfil para uma clínica ainda não cadastrada.
     */
    public function create()
    {
        $especialidades = Especialidade::where('ativa', true)->get();
        $servicos = ServicoAcessibilidade::where('ativa', true)->get();

        return view('clinica.perfil-create', compact('especialidades', 'servicos'));
    }

    /**
     * Cria o perfil de uma clínica e vincula especialidades e serviços de acessibilidade.
     * O uso de sync e attach mostra como o Laravel persiste relações em tabelas intermediárias.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'cnpj' => 'required|unique:clinicas,cnpj',
            'telefone' => 'nullable|string|max:20',
            'email_contato' => 'nullable|email',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|size:9',
            'descricao' => 'nullable|string|max:2000',
            'especialidades' => 'required|array|min:1',
            'especialidades.*' => 'exists:especialidades,id',
            'servicos_acessibilidade' => 'nullable|array',
            'servicos_acessibilidade.*' => 'exists:servicos_acessibilidade,id',
        ]);

        /** @var \App\Models\Usuario $user */
        $user = Auth::user();
        $clinica = $user->clinica()->create([
            'razao_social' => $validated['razao_social'],
            'nome_fantasia' => $validated['nome_fantasia'],
            'cnpj' => $validated['cnpj'],
            'telefone' => $validated['telefone'] ?? null,
            'email_contato' => $validated['email_contato'] ?? null,
            'logradouro' => $validated['logradouro'],
            'numero' => $validated['numero'],
            'complemento' => $validated['complemento'] ?? null,
            'bairro' => $validated['bairro'],
            'cidade' => $validated['cidade'],
            'estado' => $validated['estado'],
            'cep' => $validated['cep'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        $clinica->especialidades()->sync($validated['especialidades']);

        if (!empty($validated['servicos_acessibilidade'])) {
            $attach = array_fill_keys($validated['servicos_acessibilidade'], ['disponivel' => true]);
            $clinica->servicosAcessibilidade()->attach($attach);
        }

        return redirect()->route('dashboard')->with('success', 'Perfil da clinica criado com sucesso! Aguardando aprovacao do administrador.');
    }

    /**
     * Atualiza o perfil de uma clínica existente.
     * O método substitui as relações de especialidades e serviços por um conjunto novo.
     */
    public function update(Request $request)
    {
        $clinica = Auth::user()->clinica;

        $validated = $request->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email_contato' => 'nullable|email',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'cep' => 'required|string|size:9',
            'descricao' => 'nullable|string|max:2000',
            'especialidades' => 'required|array|min:1',
            'especialidades.*' => 'exists:especialidades,id',
            'servicos_acessibilidade' => 'nullable|array',
            'servicos_acessibilidade.*' => 'exists:servicos_acessibilidade,id',
        ]);

        $clinica->update([
            'razao_social' => $validated['razao_social'],
            'nome_fantasia' => $validated['nome_fantasia'],
            'telefone' => $validated['telefone'] ?? null,
            'email_contato' => $validated['email_contato'] ?? null,
            'logradouro' => $validated['logradouro'],
            'numero' => $validated['numero'],
            'complemento' => $validated['complemento'] ?? null,
            'bairro' => $validated['bairro'],
            'cidade' => $validated['cidade'],
            'estado' => $validated['estado'],
            'cep' => $validated['cep'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        $clinica->especialidades()->sync($validated['especialidades']);

        $servicos = $validated['servicos_acessibilidade'] ?? [];
        $clinica->servicosAcessibilidade()->sync(
            array_fill_keys($servicos, ['disponivel' => true])
        );

        return redirect()->route('dashboard')->with('success', 'Perfil atualizado com sucesso!');
    }
}
