<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Materias;
use App\Models\asistencias;
use App\Models\User;

class clases extends Model
{

    use HasFactory;
    protected $table = 'clases';
    protected $dates = ['fecha'];
    

    // Una clase pertenece a una materia
    public function materia()
    {
        return $this->belongsTo(Materias::class, 'materia_id');
    }

    // Una clase tiene muchas asistencias
    public function asistencias()
    {
        return $this->hasMany(asistencias::class, 'clase_id');
    }

    //Alumnos presentes/ausentes en la clase
    public function alumnosPresentes()
    {
        return $this->belongsToMany(User::class, 'asistencias')
                    ->withPivot('presente', 'observacion')
                    ->withTimestamps();
    }
}
