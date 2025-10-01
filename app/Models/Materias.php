<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;


class Materias extends Model
{
    use HasFactory;
    protected $table = 'Materias';

    protected $fillable = ['materias_id', 'matNombre', 'matDescripcion', 'carreras_id'];

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carreras::class);
    }
}
