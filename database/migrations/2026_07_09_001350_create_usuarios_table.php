<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('telefone')->nullable();
            $table->string('cpf')->unique();
            $table->integer('idade')->nullable();
            $table->string('endereco')->nullable();
            $table->enum('sexo', ['M', 'F', 'Outro'])->nullable();
            $table->enum('role', ['paciente', 'clinica', 'admin'])->default('paciente');
            $table->string('condicao')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
