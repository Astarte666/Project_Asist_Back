<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inscripciones extends Model
{
    use HasFactory;
    protected $table = 'inscripciones';
    protected $fillable = ['user_id', 'materias_id', 'fecha_inscripcion'];

    // Una inscripción pertenece a un alumno
    public function alumno()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Una inscripción pertenece a una materia
    public function materia()
    {
        return $this->belongsTo(Materias::class, 'materias_id');
    }
}
