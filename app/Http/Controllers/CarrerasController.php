<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarrerasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
        $carreras = Carreras::all();

        if ($carreras->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Todavía no hay carreras registradas.',
                'data' => []
            ], 200);
        }

        return response()->json([
                'success' => true,
                'message' => 'Listado de carreras obtenido correctamente.',
                'data' => $carreras
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las carreras.',
                'data' => []
            ], 500);
        }
    }

    public function showConMaterias() {

        try {
            $carreras = Carreras::with('materias')->get();

            if ($carreras->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Todavía no hay carreras registradas.',
                    'data' => []
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las carreras relacionadas con materias.',
                'data' => []
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Listado de carreras relacionadas con materias obtenido correctamente.',
            'data' => Carreras::with('materias')->get()
        ], 200);
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
        try{ 

        $request->validate([
            'carreNombre' => 'required|string|max:255|unique:carreras,carreNombre',
        ]);
        $carreras = Carreras::create($request->all());
        return response()->json([
            'success' =>true,
            'message' => 'Carrera creada correctamente.',
            'data'=>$carreras
        ], 201);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al crear la carrera.',
            'data' => []
        ], 500);
    }
    }
    /**
     * Display the specified resource.
     */
    public function show(Carreras $carreras)
    {
        return response()->json($carreras);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carreras $carreras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carreras $id)
    {
        try{ 
        $carreras = Carreras::find($id);
        $carreras-> carreNombre = $request->carreNombre;
        $carreras->save();
        return response()->json([
            'success'=>true,
            'message'=> 'Carrera editada correctamente.',
            'data'=>$carreras
        ], 200);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al editar la carrera.',
            'data' => []
        ], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
        $carreras = Carreras::destroy($id);
        return response()->json([
            'success'=>true,
            'message'=> 'Carrera eliminada correctamente.',
            'data'=>$carreras
        ], 200);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la carrera.',
            'data' => []
        ], 500);
    }
    }
}
