<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inscripciones extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $fillable = ['user_id', 'fecha_inscripcion'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // NUEVA RELACIÓN
    public function materias()
{
    return $this->belongsToMany(
        Materias::class,
        'inscripcion_materias',
        'inscripcion_id',
        'materia_id'
    );
}
}