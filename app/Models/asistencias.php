<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class asistencias extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $fillable = ['clase_id', 'user_id', 'presente', 'observacion'];

    // Asistencia pertenece a una clase
    public function clase()
    {
        return $this->belongsTo(clases::class, 'clase_id');
    }

    // Asistencia pertenece a un alumno
    public function alumno()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
