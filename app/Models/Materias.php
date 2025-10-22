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
    
    protected $table = 'Materias';

    protected $fillable = ['matNombre', 'matDescripcion', 'carreras_id'];

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carreras::class, 'carreras_id');
    }
}
