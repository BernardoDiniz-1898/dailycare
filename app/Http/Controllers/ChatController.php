<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Conversa;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Busca todas as conversas do usuario logado, seja ele paciente ou dono de clinica
    private function conversasDoUsuario()
    {
        $usuario = Auth::user();

        $query = Conversa::with(['paciente', 'clinica', 'ultimaMensagem']);

        if ($usuario->isClinica()) {
            $query->where('clinica_id', $usuario->clinica->id ?? 0);
        } else {
            $query->where('paciente_id', $usuario->id);
        }

        return $query->get()->sortByDesc(function ($conversa) {
            return $conversa->ultimaMensagem->created_at ?? $conversa->created_at;
        })->values();
    }

    // Garante que o usuario logado realmente faz parte dessa conversa
    private function autorizarAcesso(Conversa $conversa): void
    {
        $usuario = Auth::user();
        $pertence = $conversa->paciente_id === $usuario->id
            || ($usuario->clinica && $conversa->clinica_id === $usuario->clinica->id);

        abort_unless($pertence, 403);
    }

    public function index()
    {
        $conversas = $this->conversasDoUsuario();

        return view('chat.index', [
            'conversas' => $conversas,
            'conversaAtiva' => null,
            'mensagens' => collect(),
        ]);
    }

    public function show(Conversa $conversa)
    {
        $this->autorizarAcesso($conversa);

        // Marca como lidas as mensagens que nao foram enviadas pelo usuario atual
        $conversa->mensagens()
            ->where('remetente_id', '!=', Auth::id())
            ->where('lida', false)
            ->update(['lida' => true]);

        return view('chat.index', [
            'conversas' => $this->conversasDoUsuario(),
            'conversaAtiva' => $conversa,
            'mensagens' => $conversa->mensagens()->with('remetente')->get(),
        ]);
    }

    public function store(Request $request, Conversa $conversa)
    {
        $this->autorizarAcesso($conversa);

        $validado = $request->validate([
            'conteudo' => 'required|string|max:2000',
        ]);

        Mensagem::create([
            'conversa_id' => $conversa->id,
            'remetente_id' => Auth::id(),
            'conteudo' => $validado['conteudo'],
        ]);

        return redirect()->route('chat.show', $conversa);
    }

    // Chamado quando um paciente clica em "Enviar mensagem" no perfil de uma clinica
    public function iniciar(Clinica $clinica)
    {
        abort_unless(Auth::user()->isPaciente(), 403);

        $conversa = Conversa::firstOrCreate([
            'paciente_id' => Auth::id(),
            'clinica_id' => $clinica->id,
        ]);

        return redirect()->route('chat.show', $conversa);
    }
}
