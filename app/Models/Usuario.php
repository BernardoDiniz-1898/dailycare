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

    /**
     * Atributos que podem ser preenchidos via Mass Assignment.
     */
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'cpf',
        'crefito',   // <-- Adicionado aqui!
        'idade',
        'endereco',
        'sexo',
        'role',
        'condicao',
        'foto',
        'ativo',
    ];

    /**
     * Atributos ocultos nas serializações (JSON/Arrays).
     */
    protected $hidden = [
        'senha',
    ];

    /**
     * Casting de tipos automáticos do Eloquent.
     */
    protected $casts = [
        'senha' => 'hashed',
        'ativo' => 'boolean',
    ];

    /**
     * Sobrescreve a busca do campo de senha para o Laravel Auth.
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /* =========================================================================
     | RELACIONAMENTOS
     ========================================================================= */

    /**
     * Relacionamento 1 para 1 com a Clínica (quando o usuário é Fisioterapeuta/Clínica).
     */
    public function clinica()
    {
        return $this->hasOne(Clinica::class, 'usuario_id');
    }

    /**
     * Relacionamento de Agendamentos (quando o usuário é Paciente).
     */
    public function agendamentosComoPaciente()
    {
        return $this->hasMany(Agendamento::class, 'paciente_id');
    }

    /**
     * Relacionamento de Avaliações (quando o usuário é Paciente).
     */
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'paciente_id');
    }

    /* =========================================================================
     | HELPER METHODS (VERIFICAÇÃO DE PERFIS)
     ========================================================================= */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClinica(): bool
    {
        return in_array($this->role, ['clinica', 'fisioterapeuta']);
    }

    public function isPaciente(): bool
    {
        return $this->role === 'paciente';
    }
}