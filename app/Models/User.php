<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Inscripciones;
use App\Models\Materias;
use App\Models\asistencias;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'userDocumento',
        'userApellido',
        'userNombre',
        'userTelefono',
        'userProvincia',
        'userLocalidad',
        'userDomicilio',
        'userAceptado',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'userAceptado' => 'boolean', 
        ];
    }

    public function materias() 
    { 
        return $this->belongsToMany(Materias::class, 'inscripcion_materias', 'user_id', 'materia_id')
                    ->withPivot('fecha_inscripcion')
                    ->withTimestamps(); 
    }

    public function asistencias()
    {
        return $this->hasMany(asistencias::class, 'user_id');
    }

}
