<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CarrerasController extends Controller
{
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las carreras.',
                'data' => []
            ], 500);
        }
    }

    public function showMaterias($id)
    {
        try {
            $carrera = Carreras::with('materias')->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Materias de la carrera obtenidas correctamente.',
                'data' => $carrera->materias
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carrera no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener materias.',
            ], 500);
        }
    }

    public function showConMaterias()
    {
        try {
            $carreras = Carreras::with('materias')->get();

            if ($carreras->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Todavía no hay carreras registradas.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Listado de carreras con materias obtenido correctamente.',
                'data' => $carreras
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las carreras relacionadas con materias.',
                'data' => []
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'carreNombre' => 'required|string|max:255|unique:carreras,carreNombre',
            ]);

            $carrera = Carreras::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Carrera creada correctamente.',
                'data' => $carrera
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la carrera.',
                'data' => []
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $carrera = Carreras::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Carrera obtenida correctamente.',
                'data' => $carrera
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La carrera solicitada no existe.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la carrera.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $carrera = Carreras::findOrFail($id);

            $request->validate([
                'carreNombre' => 'required|string|max:255|unique:carreras,carreNombre,' . $id,
            ]);

            $carrera->carreNombre = $request->carreNombre;
            $carrera->save();

            return response()->json([
                'success' => true,
                'message' => 'Carrera editada correctamente.',
                'data' => $carrera
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La carrera a actualizar no existe.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al editar la carrera.',
                'data' => []
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $carrera = Carreras::findOrFail($id);
            $carrera->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carrera eliminada correctamente.',
                'data' => $carrera
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La carrera a eliminar no existe.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la carrera.',
                'data' => []
            ], 500);
        }
    }
}
