<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ConfiguracoesController extends Controller
{
    public function index()
    {
        return view('configuracoes.index');
    }

    public function atualizarSenha(Request $request)
    {
        $validado = $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => ['required', 'confirmed', Password::min(8)],
        ]);

        $usuario = Auth::user();

        if (!Hash::check($validado['senha_atual'], $usuario->senha)) {
            return back()->withErrors(['senha_atual' => 'A senha atual informada esta incorreta.']);
        }

        $usuario->update([
            'senha' => Hash::make($validado['nova_senha']),
        ]);

        return back()->with('success', 'Senha alterada com sucesso.');
    }
}
