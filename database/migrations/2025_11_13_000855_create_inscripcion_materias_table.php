<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('inscripcion_materias')) {
            Schema::create('inscripcion_materias', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');                      
                $table->foreignId('materia_id')->constrained()->onDelete('cascade');
                $table->date('fecha_inscripcion')->nullable();
                $table->primary(['user_id', 'materia_id']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_materias');
    }
};