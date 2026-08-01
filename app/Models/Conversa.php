<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    protected $fillable = [
        'paciente_id',
        'clinica_id',
    ];

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'paciente_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'clinica_id');
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class)->orderBy('created_at');
    }

    public function ultimaMensagem()
    {
        return $this->hasOne(Mensagem::class)->latestOfMany();
    }

    // Nome de quem aparece pra representar essa conversa, dependendo de quem esta olhando
    public function nomeParaUsuario(Usuario $usuario): string
    {
        if ($usuario->id === $this->paciente_id) {
            return $this->clinica->nome_fantasia;
        }

        return $this->paciente->nome;
    }

    public function mensagensNaoLidasPara(Usuario $usuario): int
    {
        return $this->mensagens()
            ->where('remetente_id', '!=', $usuario->id)
            ->where('lida', false)
            ->count();
    }
}
