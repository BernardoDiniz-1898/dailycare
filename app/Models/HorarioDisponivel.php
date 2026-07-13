<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioDisponivel extends Model
{
    use HasFactory;

    protected $table = 'horarios_disponiveis';

    protected $fillable = ['clinica_id', 'dia_semana', 'hora_inicio', 'hora_fim', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
