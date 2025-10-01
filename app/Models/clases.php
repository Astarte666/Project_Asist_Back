<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class clases extends Model
{

    use HasFactory;
    protected $table = 'clases';
    protected $fillable = ['fecha', 'tema'];

    // Una clase pertenece a una materia
    public function materia()
    {
        return $this->belongsTo(Materias::class);
    }

    // Una clase tiene muchas asistencias
    public function asistencias()
    {
        return $this->hasMany(asistencias::class);
    }
}
