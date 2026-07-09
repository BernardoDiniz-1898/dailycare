<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicaFoto extends Model
{
    use HasFactory;

    protected $table = 'clinica_fotos';

    protected $fillable = ['clinica_id', 'caminho', 'legenda', 'principal'];

    protected $casts = ['principal' => 'boolean'];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
