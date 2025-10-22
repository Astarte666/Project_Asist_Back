<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;




class Carreras extends Model
{
    use HasFactory, HasApiTokens, HasRoles; 
    
    protected $table = 'Carreras';
    protected $fillable =['carreNombre'];

    public function materias(): HasMany
        {
        return $this->hasMany(Materias::class);
        }
}
