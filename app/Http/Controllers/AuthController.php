<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Cria um novo usuário após validar as regras de cadastro.
     * O uso de Hash::make garante que a senha fique protegida no banco.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => ['required', 'confirmed', Password::min(8)],
            'cpf' => 'required|unique:usuarios,cpf',
            'telefone' => 'nullable|string|max:20',
            'role' => 'required|in:paciente,clinica',
        ]);

        $usuario = Usuario::create([
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'senha' => Hash::make($validated['senha']),
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'] ?? null,
            'role' => $validated['role'],
        ]);

        Auth::login($usuario);

        return redirect()->route('dashboard');
    }

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
