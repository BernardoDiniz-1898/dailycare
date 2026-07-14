<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';
    protected $fillable = ['paciente_id', 'clinica_id', 'nota', 'comentario', 'visivel'];

    protected $casts = [
        'nota' => 'integer',
        'visivel' => 'boolean',
    ];

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'paciente_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
