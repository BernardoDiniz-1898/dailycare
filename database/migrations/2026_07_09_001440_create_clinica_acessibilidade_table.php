<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinica_acessibilidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas')->onDelete('cascade');
            $table->foreignId('servico_acessibilidade_id')->constrained('servicos_acessibilidade')->onDelete('cascade');
            $table->boolean('disponivel')->default(true);
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->unique(['clinica_id', 'servico_acessibilidade_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinica_acessibilidade');
    }
};
