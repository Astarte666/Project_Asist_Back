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
        $inscripciones = inscripciones::with(['materias', 'user'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json($inscripciones);
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
            'carrera_id' => 'required|exists:carreras,id',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
        ]);

        // Crear inscripción
        $inscripcion = inscripciones::create([
            'user_id' => $request->user_id,
            'carrera_id' => $request->carrera_id,
            'fecha_inscripcion' => now(),
        ]);

        // Asignar materias (cualquier carrera)
        foreach ($request->materias as $materia_id) {
            $inscripcion->materias()->attach($materia_id, [
                'user_id' => $inscripcion->user_id,
                'fecha_inscripcion' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return response()->json([
            'message' => 'Inscripción completa con materias',
            'inscripcion_id' => $inscripcion->id
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
