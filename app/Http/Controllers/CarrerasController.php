<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use App\Models\Materias;
use App\Models\User;
use App\Http\Controllers\Controller;
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

    public function listaSelect()
    {
        try {
            $carreras = Carreras::select('id', 'carreNombre as nombre')->get();

            if ($carreras->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No hay carreras registradas.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lista de carreras obtenida correctamente.',
                'data' => $carreras
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las carreras.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function estudiantesInscriptos($carrera_id)
    {
        try {
            $carrera = Carreras::with('materias')->findOrFail($carrera_id);
            $estudiantes = User::whereHas('roles', function ($query) {
                    $query->where('name', 'estudiante');
                })
                ->whereHas('materias', function ($query) use ($carrera) {
                    $query->whereIn('materias.id', $carrera->materias->pluck('id'));
                })
                ->with(['materias' => function ($query) use ($carrera) {
                    $query->whereIn('materias.id', $carrera->materias->pluck('id'))
                        ->select('materias.id', 'materias.matNombre as nombre');
                }])
                ->select('users.id', 'userNombre as nombre', 'userApellido as apellido', 'userDocumento as documento')
                ->get()
                ->map(function ($estudiante) use ($carrera) {
                    return [
                        'id' => $estudiante->id,
                        'nombre_completo' => $estudiante->apellido . ', ' . $estudiante->nombre,
                        'documento' => $estudiante->documento,
                        'carrera' => $carrera->carreNombre,
                        'materias' => $estudiante->materias->pluck('nombre')
                    ];
                });
            if ($estudiantes->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No hay estudiantes inscriptos en esta carrera.',
                    'data' => []
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Estudiantes inscriptos obtenidos correctamente.',
                'data' => $estudiantes
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carrera no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los estudiantes.',
                'error' => $e->getMessage()
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

    public function showCurso($id)  
    {
        try {
            $carrera = Carreras::with([
                'materias' => function($query) {
                    $query->with(['profesores' => function($q) {
                        $q->select('users.id', 'userNombre', 'userApellido', 'email');  
                    }, 'estudiantes' => function($q) {
                        $q->select('users.id', 'userNombre', 'userApellido', 'userDocumento'); 
                    }]);
                }
            ])->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Curso obtenido correctamente.',
                'data' => $carrera
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el curso.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'carreNombre' => 'required|string|max:255',
                'materias' => 'required|array',
                'materias.*' => 'exists:materias,id'
            ]);

            $carrera = Carreras::create([
                'carreNombre' => $validated['carreNombre']
            ]);

            Materias::whereIn('id', $validated['materias'])
                    ->update(['carreras_id' => $carrera->id]);

            return response()->json([
                'success' => true,
                'message' => 'Carrera creada y materias asignadas.',
                'data' => $carrera
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la carrera.',
                'error' => $e->getMessage()
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
