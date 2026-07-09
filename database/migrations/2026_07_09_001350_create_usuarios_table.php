<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->nome("string");
            $table->idade("integer");
            $table->senha("string")->hashed;
            $table->email("string");
            $table->telefone("string");
            $table->CPF("string");
            $table->endereco("string");
            $table->sexo("string");
            $table->condicao("string")->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
