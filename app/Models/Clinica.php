<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clinicas';

    protected $fillable = [
        'usuario_id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'telefone',
        'email_contato',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'latitude',
        'longitude',
        'descricao',
        'foto_capa',
        'status',
        'ativa',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'ativa' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'clinica_especialidade');
    }

    public function servicosAcessibilidade()
    {
        return $this->belongsToMany(ServicoAcessibilidade::class, 'clinica_acessibilidade')
            ->withPivot(['disponivel', 'observacao']);
    }

    public function fotos()
    {
        return $this->hasMany(ClinicaFoto::class);
    }

    public function horarios()
    {
        return $this->hasMany(HorarioDisponivel::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    public function mediaAvaliacoes(): float
    {
        return round($this->avaliacoes()->avg('nota') ?? 0, 1);
    }

    public function totalAvaliacoes(): int
    {
        return $this->avaliacoes()->count();
    }

    public function enderecoCompleto(): string
    {
        return "{$this->logradouro}, {$this->numero} - {$this->bairro}, {$this->cidade} - {$this->estado}, {$this->cep}";
    }

    public function scopeAprovadas($query)
    {
        return $query->where('status', 'aprovada')->where('ativa', true);
    }
}
