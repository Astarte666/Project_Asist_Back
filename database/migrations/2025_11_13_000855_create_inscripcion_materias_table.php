<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_inscripcion')->nullable();
            $table->timestamps();
            $table->unique(['materia_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_materias');
    }
};