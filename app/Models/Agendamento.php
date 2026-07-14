<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agendamentos';

    protected $fillable = [
        'paciente_id',
        'clinica_id',
        'data',
        'hora',
        'status',
        'observacao_paciente',
        'observacao_clinica',
    ];

    protected $casts = ['data' => 'date'];

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'paciente_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'solicitado' => 'Solicitado',
            'confirmado' => 'Confirmado',
            'recusado' => 'Recusado',
            'cancelado' => 'Cancelado',
            'concluido' => 'Concluido',
            default => $this->status,
        };
    }

    public function statusCor(): string
    {
        return match ($this->status) {
            'solicitado' => 'bg-yellow-100 text-yellow-800',
            'confirmado' => 'bg-green-100 text-green-800',
            'recusado' => 'bg-red-100 text-red-800',
            'cancelado' => 'bg-gray-100 text-gray-800',
            'concluido' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
