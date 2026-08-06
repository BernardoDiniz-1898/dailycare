<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $fillable = [
        'clinica_id',
        'plano_id',
        'periodo',
        'status',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function ativa(): bool
    {
        return $this->status === 'ativa' && $this->data_fim->isFuture();
    }
}
