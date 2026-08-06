<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'clinica_id',
        'titulo',
        'descricao',
        'imagem',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
