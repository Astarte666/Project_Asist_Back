<?php

namespace App\Http\Controllers;

use App\Models\clases;
use App\Models\Materias;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Clases::with('materias')->get();
        return response()->json('Lista de clases obtenida correctamente', 200);
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
                'materias_id' => 'required|exists:materias,id',
                'fecha' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $clase = Clase::create([
                'materias_id' => $request->materias_id,
                'fecha' => $request->fecha,
            ]);

            return response()->json([
                'message' => 'Clase creada correctamente',
                'clase' => $clase
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la clase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $clase = Clases::with('materias')->find('id');
        
        if(!$clase){
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }

        return response()->json($clase);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(clases $clases)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $clases = Clases::find($id);
        $clases->update($request->all());
        return response()->json($clases);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clases = Clases::find($id);
        if (!$clases) {
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }
        $clases->delete();
        return response()->json(['message' => 'Clase eliminada correctamente']);
    }
}
