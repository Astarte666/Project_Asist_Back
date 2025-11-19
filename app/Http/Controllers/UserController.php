<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\inscripciones;
use App\Models\Materias;
use App\Models\Carreras;

class UserController extends Controller
{
    public function userCarrerasMaterias(Request $request)
    {
        $user = auth()->user();

    $inscripciones = $user->inscripciones()
        ->with('materias.carrera')
        ->get();

    // Reagrupamos las materias por carrera, conservando la fecha
    $carreras = [];

    foreach ($inscripciones as $inscripcion) {
        foreach ($inscripcion->materias as $materia) {

            $nombreCarrera = $materia->carrera->carreNombre ?? 'Sin Carrera';

            if (!isset($carreras[$nombreCarrera])) {
                $carreras[$nombreCarrera] = [
                    'fecha' => $inscripcion->fecha_inscripcion,
                    'materias' => []
                ];
            }

            $carreras[$nombreCarrera]['materias'][] = $materia;
        }
    }

    return response()->json([
        'success' => true,
        'carreras' => $carreras
    ]);
    }
}
