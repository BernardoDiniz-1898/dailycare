@extends('layouts.app')

@section('titulo', 'Mensagens')

@section('conteudo')
<div class="chat-container">
    {{-- Lista de conversas --}}
    <aside class="chat-lista">
        <div class="chat-lista-topo">
            <h1><i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Mensagens</h1>
        </div>

        @if ($conversas->count() > 0)
            <div class="chat-lista-itens">
                @foreach ($conversas as $conversa)
                    @php
                        $naoLidas = $conversa->mensagensNaoLidasPara(Auth::user());
                        $nome = $conversa->nomeParaUsuario(Auth::user());
                        $ultima = $conversa->ultimaMensagem;
                    @endphp
                    <a href="{{ route('chat.show', $conversa) }}"
                       class="chat-item {{ $conversaAtiva && $conversaAtiva->id === $conversa->id ? 'ativo' : '' }}">
                        <div class="chat-item-avatar">{{ strtoupper(substr($nome, 0, 1)) }}</div>
                        <div class="chat-item-info">
                            <div class="chat-item-linha-topo">
                                <span class="chat-item-nome">{{ $nome }}</span>
                                @if ($ultima)
                                    <span class="chat-item-horario">{{ $ultima->created_at->format('d/m H:i') }}</span>
                                @endif
                            </div>
                            <div class="chat-item-linha-baixo">
                                <span class="chat-item-preview">
                                    {{ $ultima ? \Illuminate\Support\Str::limit($ultima->conteudo, 34) : 'Nenhuma mensagem ainda' }}
                                </span>
                                @if ($naoLidas > 0)
                                    <span class="chat-badge-nao-lida">{{ $naoLidas }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="chat-lista-vazia">
                <i class="bi bi-chat-dots" aria-hidden="true"></i>
                <p>Nenhuma conversa ainda.</p>
                <p style="font-size:0.875rem;">Envie uma mensagem a partir do perfil de uma clinica pra comecar.</p>
            </div>
        @endif
    </aside>

    {{-- Conversa ativa --}}
    <section class="chat-thread">
        @if ($conversaAtiva)
            @php
                $nomeOutro = $conversaAtiva->nomeParaUsuario(Auth::user());
            @endphp
            <div class="chat-thread-topo">
                <div class="chat-item-avatar">{{ strtoupper(substr($nomeOutro, 0, 1)) }}</div>
                <div>
                    <h2>{{ $nomeOutro }}</h2>
                    @if (Auth::user()->isPaciente())
                        <a href="{{ route('clinicas.show', $conversaAtiva->clinica) }}" class="chat-thread-link">Ver perfil da clinica</a>
                    @endif
                </div>
            </div>

            <div class="chat-thread-mensagens" id="chat-mensagens">
                @forelse ($mensagens as $mensagem)
                    @php $minha = $mensagem->remetente_id === Auth::id(); @endphp
                    <div class="chat-bolha-wrap {{ $minha ? 'minha' : 'outro' }}">
                        <div class="chat-bolha">
                            <p>{{ $mensagem->conteudo }}</p>
                            <span class="chat-bolha-horario">{{ $mensagem->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <p style="text-align:center; color:var(--color-text-secondary); margin-top:40px;">
                        Essa e o inicio da sua conversa com {{ $nomeOutro }}.
                    </p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('chat.store', $conversaAtiva) }}" class="chat-thread-form">
                @csrf
                <input type="text" name="conteudo" class="form-input" placeholder="Digite sua mensagem..."
                       autocomplete="off" required autofocus>
                <button type="submit" class="btn btn-primary" aria-label="Enviar mensagem">
                    <i class="bi bi-send-fill" aria-hidden="true"></i>
                </button>
            </form>
        @else
            <div class="chat-thread-vazia">
                <i class="bi bi-chat-square-text" aria-hidden="true"></i>
                <p>Selecione uma conversa para comecar</p>
            </div>
        @endif
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mensagens = document.getElementById('chat-mensagens');
        if (mensagens) {
            mensagens.scrollTop = mensagens.scrollHeight;
        }
    });
</script>
@endsection
