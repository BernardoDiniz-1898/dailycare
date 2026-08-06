<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = [
        'conversa_id',
        'remetente_id',
        'conteudo',
        'lida',
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function conversa()
    {
        return $this->belongsTo(Conversa::class);
    }

    public function remetente()
    {
        return $this->belongsTo(Usuario::class, 'remetente_id');
    }
}
