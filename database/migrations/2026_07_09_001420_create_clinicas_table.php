<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('razao_social');
            $table->string('nome_fantasia');
            $table->string('cnpj')->unique();
            $table->string('telefone')->nullable();
            $table->string('email_contato')->nullable();
            $table->string('logradouro');
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('cep', 9);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('descricao')->nullable();
            $table->string('foto_capa')->nullable();
            $table->enum('status', ['pendente', 'aprovada', 'rejeitada'])->default('pendente');
            $table->boolean('ativa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinicas');
    }
};
