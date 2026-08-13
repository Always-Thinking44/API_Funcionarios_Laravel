<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();

            // Dono do funcionário
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Departamento do funcionário
            $table->foreignId('department_id')
                ->constrained('departamentos')
                ->restrictOnDelete();

            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
