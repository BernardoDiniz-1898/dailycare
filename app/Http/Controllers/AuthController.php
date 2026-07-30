<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Clinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador de autenticação.
 * Responsável por exibir formulários de login e cadastro, validar credenciais
 * e gerenciar a sessão do usuário com o Laravel Auth.
 */
class AuthController extends Controller
{
    /**
     * Retorna a view de login para o usuário acessar o sistema.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Processa o login do usuário.
     * A validação de dados e a checagem de senha são exemplos de semântica de entrada/saída.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where('email', $credentials['email'])->first();

        if (!$usuario || !Hash::check($credentials['senha'], $usuario->senha)) {
            return back()->withErrors(['email' => 'Credenciais invalidas.'])->withInput($request->only('email'));
        }

        if (!$usuario->ativo) {
            return back()->withErrors(['email' => 'Conta desativada.'])->withInput($request->only('email'));
        }

        Auth::login($usuario, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Retorna a view de cadastro para novos usuários.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Cria um novo usuário (e sua clínica, se aplicável) após validar as regras.
     * Utiliza DB::transaction para garantir a integridade do banco de dados.
     */
    public function register(Request $request)
    {
        $isClinica = $request->input('role') === 'fisioterapeuta';

        // 1. Regras de validação baseadas no tipo de conta escolhida
        $rules = [
            'role'     => 'required|in:paciente,fisioterapeuta',
            'email'    => 'required|email|unique:usuarios,email',
            'senha'    => ['required', 'confirmed', Password::min(8)],
            'telefone' => 'nullable|string|max:20',
        ];

        if ($isClinica) {
            $rules['nome_responsavel'] = 'required|string|max:255';
            $rules['razao_social']     = 'required|string|max:255';
            $rules['nome_fantasia']     = 'required|string|max:255';
            $rules['cnpj']              = 'required|string|max:18|unique:clinicas,cnpj';
            $rules['cep']               = 'required|string|max:9';
            $rules['logradouro']        = 'required|string|max:255';
            $rules['numero']            = 'required|string|max:20';
            $rules['bairro']            = 'required|string|max:255';
            $rules['cidade']            = 'required|string|max:255';
            $rules['estado']            = 'required|string|max:2';
        } else {
            $rules['nome']     = 'required|string|max:255';
            $rules['cpf']      = 'required|string|max:14|unique:usuarios,cpf';
            $rules['endereco'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        // 2. Transação no Banco de Dados
        DB::transaction(function () use ($validated, $isClinica) {

            // Cria a conta principal na tabela 'usuarios'
            $usuario = Usuario::create([
                'nome'     => $isClinica ? $validated['nome_responsavel'] : $validated['nome'],
                'email'    => $validated['email'],
                'senha'    => Hash::make($validated['senha']),
                'cpf'      => $isClinica ? null : $validated['cpf'],
                'endereco' => $isClinica ? null : ($validated['endereco'] ?? null),
                'telefone' => $validated['telefone'] ?? null,
                'role'     => $validated['role'],
            ]);

            // Se for fisioterapeuta/clínica, vincula o cadastro na tabela 'clinicas'
            if ($isClinica) {
                Clinica::create([
                    'usuario_id'    => $usuario->id,
                    'razao_social'  => $validated['razao_social'],
                    'nome_fantasia' => $validated['nome_fantasia'],
                    'cnpj'          => $validated['cnpj'],
                    'telefone'      => $validated['telefone'] ?? null,
                    'email_contato' => $validated['email'],
                    'cep'           => $validated['cep'],
                    'logradouro'    => $validated['logradouro'],
                    'numero'        => $validated['numero'],
                    'bairro'        => $validated['bairro'],
                    'cidade'        => $validated['cidade'],
                    'estado'        => strtoupper($validated['estado']),
                    'status'        => 'pendente', // Mantém o enum padrão da migration
                ]);
            }

            // Realiza o login do usuário recém-criado
            Auth::login($usuario);
        });

        return redirect()->route('dashboard');
    }
    // fim do cadastro

    /**
     * Finaliza a sessão do usuário autenticado.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}