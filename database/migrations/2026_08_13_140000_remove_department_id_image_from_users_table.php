<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Na estrutura atual os funcionários são registos próprios na tabela
     * `funcionarios` (com o seu próprio department_id e image), pelo que
     * estas colunas em `users` deixaram de ser necessárias.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'image']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('id')
                ->constrained('departamentos')->nullOnDelete();
            $table->string('image')->nullable()->after('password');
        });
    }
};
