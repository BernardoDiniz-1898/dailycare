<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('clinica_id')->constrained('clinicas')->onDelete('cascade');
            $table->date('data');
            $table->time('hora');
            $table->enum('status', ['solicitado', 'confirmado', 'recusado', 'cancelado', 'concluido'])->default('solicitado');
            $table->text('observacao_paciente')->nullable();
            $table->text('observacao_clinica')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
