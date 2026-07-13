<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    use HasFactory;

    protected $table = 'especialidades';

    protected $fillable = ['nome', 'descricao', 'ativa'];

    protected $casts = ['ativa' => 'boolean'];

    public function clinicas()
    {
        return $this->belongsToMany(Clinica::class, 'clinica_especialidade');
    }
}
