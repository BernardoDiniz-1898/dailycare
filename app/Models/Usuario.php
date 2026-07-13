<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'cpf',
        'idade',
        'endereco',
        'sexo',
        'role',
        'condicao',
        'foto',
        'ativo',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'senha' => 'hashed',
        'ativo' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function clinica()
    {
        return $this->hasOne(Clinica::class);
    }

    public function agendamentosComoPaciente()
    {
        return $this->hasMany(Agendamento::class, 'paciente_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'paciente_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClinica(): bool
    {
        return $this->role === 'clinica';
    }

    public function isPaciente(): bool
    {
        return $this->role === 'paciente';
    }
}
