<?php

namespace App\Http\Controllers;

use App\Models\Materias;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MateriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $materias = Materias::all();

            if ($materias->isEmpty()){
                return response()->json([
                    'success' => true,
                    'message' => 'Todavía no hay materias registradas.',
                    'data' => []
                ], 200);
            }

        return response()->json([
            'success' =>true,
            'message' => 'Listado de materias obtenido correctamente',
            'data' =>$materias
        ], 200);

    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener las materias.',
            'data' => []
        ], 500);
    }}


    public function showMateriasConCarreras() {

    try{
        $materias = Materias::with('carreras')->get();
        return response()->json($materias);
        if($materias->isEmpty()){
            return response()->json([
                'success'=>true,
                'message'=>'Todavía no hay materias registradas',
                'data'=>[]
            ], 200);
        }
    return response()->json([
            'success' => true,
            'message' => 'Listado de materias con sus carreras obtenido correctamente.',
            'data' => $materias
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message'=> 'Error al obtener las materias relacionadas con la carrera',
            'data'=>[]
        ], 500); 
        }
    }

    public function estudiantesInscriptos($materia_id)
{
    try {
        $materia = Materias::with('estudiantes')->findOrFail($materia_id);

        $estudiantes = $materia->estudiantes()
            ->select('users.id', 'userNombre as nombre', 'userApellido as apellido', 'userDocumento as documento')
            ->get();

        if ($estudiantes->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No hay alumnos inscriptos en esta materia.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alumnos inscriptos obtenidos correctamente.',
            'data' => $estudiantes
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Materia no encontrada.'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los alumnos.',
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
        try{ 
        $request->validate([
            'matNombre' => 'required|string|max:255',
            'carreras_id' => 'required|exists:carreras,id',
        ]);

        $materias = Materias::create($request->all());
        return response()->json([
            'success'=>true,
            'message'=> 'Materia creada correctamente',
            'data'=>$materias
        ], 201);
    } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors()
            ], 400);
    }catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message'=> 'Error al crear la materia',
            'data'=>[]
        ], 500); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materias $materias)
    {
        try {
            $materia = Materias::with('carreras')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Materia obtenida correctamente.',
                'data' => $materia
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La materia solicitada no existe.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la materia.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materias $materias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materias $materias)
    {
        try{ 
        $request->validate([
            'matNombre' => 'sometimes|required|string|max:255',
            'carreras_id' => 'sometimes|required|exists:carreras,id',
        ]);

            $materias->update($request->all());
            return response()->json([
                'success'=>true,
                'message'=> 'Materia editada correctamente.',
                'data'=>$materias
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La materia que intentas actualizar no existe.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors()
            ], 400);    
        } catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message'=> 'Error al editar la materia.',
            'data'=>[]
        ], 500); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materias $materias)
    {
        try{
            $materias = Materias::destroy($id);
            return response()->json([
                'success'=>true,
                'message'=> 'Materia eliminada correctamente.',
                'data'=>$materias
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La materia que intentas eliminar no existe.',
            ], 404);    
        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=> 'Error al eliminar la materia.',
                'data'=>[]
            ], 500); 
            }
    }
}
