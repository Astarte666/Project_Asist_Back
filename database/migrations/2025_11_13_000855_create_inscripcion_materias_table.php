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
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->timestamps();

            // ← ESTO VA EN LA TABLA PIVOTE
            $table->unique(['inscripcion_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_materias');
    }
};