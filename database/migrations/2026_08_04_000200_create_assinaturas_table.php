<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
            $table->foreignId('plano_id')->constrained('planos')->cascadeOnDelete();
            $table->enum('periodo', ['mensal', 'anual'])->default('mensal');
            $table->enum('status', ['ativa', 'cancelada', 'expirada'])->default('ativa');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
