<?php

namespace App\Http\Controllers;

use App\Models\inscripciones;
use App\Models\User;
use App\Models\Materias;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class InscripcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inscripciones = inscripciones::with(['user', 'materias'])->get();
        return response()->json($inscripciones);
    }

    /* Lista de inscripciones de un usuario */
    public function misInscripciones(Request $request)
    {
        $user = $request->user();
        
        $materias = $user->materias()
            ->with('carrera')
            ->get()
            ->groupBy(function($materia) {
                return $materia->carrera->carreNombre ?? 'Sin Carrera';
            })
            ->map(function($materiasPorCarrera, $nombreCarrera) {
                return [
                    'carrera' => $nombreCarrera,
                    'materias' => $materiasPorCarrera->map(function($materia) {
                        return [
                            'id' => $materia->id,
                            'nombre' => $materia->matNombre,
                            'fecha_inscripcion' => $materia->pivot->fecha_inscripcion
                        ];
                    })
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => $materias
        ]);
    }

    public function materiasPorCarrera($carrera_id)
    {
        try {
            $materias = Materias::where('carreras_id', $carrera_id)
                ->select('id', 'matNombre as nombre')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $materias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener materias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
        ]);

        $user = User::find($request->user_id);

        foreach ($request->materias as $materia_id) {
            if (!$user->materias()->where('materia_id', $materia_id)->exists()) {
                $user->materias()->attach($materia_id, [
                    'fecha_inscripcion' => now(),
                ]);
            }
        }

        $materiasInscritas = $user->materias()->with('carrera')->get()->groupBy(function($materia) {
        return $materia->carrera->carreNombre ?? 'Sin Carrera';
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Inscripción completada correctamente',
            'data' => $materiasInscritas
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(inscripciones $id)
    {
        $inscripciones = inscripciones::with(['user', 'materias'])->find($id);
        if (!$inscripciones) {
            return response()->json(['message'=> 'Inscripcion no encontrada'], 404);
        }
        return response()->json($inscripciones, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(inscripciones $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, inscripciones $inscripciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inscripciones = inscripciones::find($id);
        if(!$inscripciones){
            return response()->json(['message' => 'Inscripcion no encontrada'], 404);
        }
        $inscripciones->delete();
        return response()->json(['message' => 'Inscripcion eliminada correctamente'], 200);
    }
}
