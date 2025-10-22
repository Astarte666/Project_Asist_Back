<?php

namespace App\Http\Controllers;

use App\Models\inscripciones;
use App\Models\User;
use App\Models\Materias;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Iluminate\Support\Facades\Validator;


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
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:users,id',
                'materias_id' => 'required|exists:materias,id',
                'fecha_inscripcion' => 'required|date',
            ]);
            if($validator->fails()){
                return response()->json(['error' =>$validator->errors()], 422);
            }
            $existe = inscripciones::where('id', $request->id)
            ->where('materias_id', $request->materias_id)
            ->exists();
            if ($existe) {
                return response()->json(['message' => 'El usuario ya está inscrito en esta materia'], 409);
            }
            $inscripciones = inscripciones::create([
                'id' => $request->id,
                'materias_id' => $request->materias_id,
                'fecha_inscripcion' => $request->fecha_inscripcion,
            ]);
            return response()->json([
                'message' => 'Inscripción creada correctamente',
                'inscripciones' => $inscripciones
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear inscripción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(inscripciones $inscripciones)
    {
        $inscripciones = inscripciones::with(['user', 'materias'])->find($id);
        if (!$inscripciones) {
            return response()->json(['message'=> 'Inscripcion no encontrada'], 404);
        }
        return response()->json($inscripciones);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(inscripciones $inscripciones)
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
    public function destroy(inscripciones $inscripciones)
    {
        $inscripciones = inscripciones::find($id);
        if(!$inscripciones){
            return response()->json(['message' => 'Inscripcion no encontrada'], 404);
        }
        $inscripciones->delete();
        return response()->json(['message' => 'Inscripcion eliminada correctamente']);
    }
}
