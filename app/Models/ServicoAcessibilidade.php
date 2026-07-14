<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoAcessibilidade extends Model
{
    use HasFactory;

    protected $table = 'servicos_acessibilidade';

    protected $fillable = ['nome', 'icone', 'ativa'];

    protected $casts = ['ativa' => 'boolean'];

    public function clinicas()
    {
        return $this->belongsToMany(Clinica::class, 'clinica_acessibilidade')
            ->withPivot(['disponivel', 'observacao']);
    }
}
