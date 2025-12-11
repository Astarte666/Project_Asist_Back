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
        $materias = $user->materias()->with('carrera')->get();
        
        // Agrupar por carrera
        $carreras = [];
        
        foreach ($materias as $materia) {
            $nombreCarrera = $materia->carrera->carreNombre ?? 'Sin Carrera';
            
            if (!isset($carreras[$nombreCarrera])) {
                $carreras[$nombreCarrera] = [
                    'fecha' => $materia->pivot->fecha_inscripcion,
                    'materias' => []
                ];
            }
            
            $carreras[$nombreCarrera]['materias'][] = $materia;
        }
        
        return response()->json([
            'success' => true,
            'carreras' => $carreras
        ]);
    }
}
