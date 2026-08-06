<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
            $table->timestamps();

            // Uma conversa unica por par paciente/clinica
            $table->unique(['paciente_id', 'clinica_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversas');
    }
};
