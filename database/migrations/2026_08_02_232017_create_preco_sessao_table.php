<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinicas', function (Blueprint $table) {
            $table->decimal('preco_sessao', 8, 2)->nullable()->after('descricao');
        });
    }

    public function down(): void
    {
        Schema::table('clinicas', function (Blueprint $table) {
            $table->dropColumn('preco_sessao');
        });
    }
};