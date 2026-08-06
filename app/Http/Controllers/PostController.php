<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $clinica = Auth::user()->clinica;
        abort_unless($clinica, 403);

        // So pode publicar quem tem uma assinatura ativa (o "aluguel do espaco" na plataforma)
        if (!$clinica->temPlanoAtivo()) {
            return redirect()->route('planos.index')
                ->with('erro_plano', 'Voce precisa de um plano ativo para criar publicacoes.');
        }

        $validado = $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'required|string|max:1000',
            'imagem' => 'nullable|url|max:500',
        ]);

        $clinica->posts()->create($validado);

        return back()->with('success', 'Publicacao criada com sucesso.');
    }

    public function destroy(Post $post)
    {
        $clinica = Auth::user()->clinica;
        abort_unless($clinica && $post->clinica_id === $clinica->id, 403);

        $post->delete();

        return back()->with('success', 'Publicacao removida.');
    }
}
