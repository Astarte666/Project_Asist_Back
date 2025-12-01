<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('materia_user', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('materia_id');
        $table->unsignedBigInteger('user_id');
        $table->date('fecha_inscripcion')->nullable();
        $table->timestamps();
        $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_user');
    }
};
