<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;



class Materias extends Model
{
    use HasFactory, HasApiTokens, HasRoles;
    
    protected $fillable = ['matNombre', 'carreras_id'];

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carreras::class, 'carreras_id');
    }

    // Estudiantes 
    public function estudiantes()
    {
        return $this->belongsToMany(User::class, 'inscripcion_materias', 'materia_id', 'user_id')
                    ->whereHas('roles', function($q) { $q->where('name', 'estudiante'); })
                    ->withPivot('fecha_inscripcion', 'fecha_inscripcion')
                    ->withTimestamps();
    }

    // Profesores
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'materia_profesor', 'materia_id', 'user_id')
                    ->whereHas('roles', function($q) { $q->where('name', 'profesor'); })
                    ->withTimestamps();
    }
}
