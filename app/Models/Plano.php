<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'preco_mensal',
        'preco_anual',
        'permite_posts',
        'prioridade_busca',
        'beneficios',
    ];

    protected $casts = [
        'permite_posts' => 'boolean',
        'prioridade_busca' => 'boolean',
        'beneficios' => 'array',
    ];

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }
}
