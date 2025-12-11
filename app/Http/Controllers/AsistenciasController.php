<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\asistencias;
use App\Models\User;
use App\Models\clases;
use App\Models\Materias;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AsistenciasController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('estudiante')) {
            $asistencias = asistencias::with(['clase.materia', 'alumno'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $query = asistencias::with(['clase.materia', 'alumno']);
            
            if ($user->hasRole('profesor')) {
                $materiasIds = $user->materias()->pluck('materias.id');
                $query->whereHas('clase.materia', function($q) use ($materiasIds) {
                    $q->whereIn('materias.id', $materiasIds);
                });
            }
            
            $asistencias = $query->orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $asistencias
        ], 200);
    }


    public function prepararTomarAsistencia($clase_id)
    {
        $user = request()->user();
        
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $clase = Clases::with('materia.carrera')->findOrFail($clase_id);
        
        if ($user->hasRole('profesor')) {
            $estaAsignado = $user->materias()->where('materias.id', $clase->materia->id)->exists();
            if (!$estaAsignado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para tomar asistencia en esta materia.'
                ], 403);
            }
        }

        $alumnos = $clase->materia->estudiantes()
            ->select('users.id', 'userNombre', 'userApellido', 'userDocumento')
            ->orderBy('userApellido')
            ->orderBy('userNombre')
            ->get();

        if ($alumnos->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No hay alumnos inscriptos en esta materia.',
                'data' => [
                    'clase' => $clase,
                    'alumnos' => [],
                    'asistencias_previas' => []
                ]
            ], 200);
        }

        $asistenciasPrevias = asistencias::where('clase_id', $clase_id)
            ->get()
            ->keyBy('user_id')
            ->map(function($a) {
                return [
                    'condicion' => $a->condicion,
                    'observacion' => $a->observacion
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Listado de alumnos obtenido correctamente.',
            'data' => [
                'clase' => $clase,
                'alumnos' => $alumnos,
                'asistencias_previas' => $asistenciasPrevias,
                'ya_tomada' => !$asistenciasPrevias->isEmpty()
            ]
        ], 200);
    }


    public function guardarAsistencias(Request $request, $clase_id)
    {
        $user = $request->user();
        
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $clase = Clases::with('materia')->findOrFail($clase_id);

        if ($user->hasRole('profesor')) {
            $estaAsignado = $user->materias()->where('materias.id', $clase->materia->id)->exists();
            if (!$estaAsignado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para tomar asistencia en esta materia.'
                ], 403);
            }
        }

        $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*.user_id' => 'required|exists:users,id',
            'asistencias.*.condicion' => 'required|in:presente,ausente,justificado',
            'asistencias.*.observacion' => 'nullable|string|max:255',
        ]);

        $alumnosInscriptos = $clase->materia->estudiantes()->pluck('users.id')->toArray();
        
        foreach ($request->asistencias as $item) {
            if (!in_array($item['user_id'], $alumnosInscriptos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uno o más alumnos no están inscriptos en la materia.'
                ], 403);
            }
        }

        try {
            DB::transaction(function () use ($request, $clase_id) {
                asistencias::where('clase_id', $clase_id)->delete();
            
                foreach ($request->asistencias as $item) {
                    asistencias::create([
                        'clase_id' => $clase_id,
                        'user_id' => $item['user_id'],
                        'condicion' => $item['condicion'],
                        'observacion' => $item['observacion'] ?? null,
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Asistencias guardadas correctamente.'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar asistencias.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $request->validate([
            'condicion' => 'sometimes|in:presente,ausente,justificado',
            'observacion' => 'nullable|string|max:255',
        ]);

        $asistencia = asistencias::findOrFail($id);
        
        if ($user->hasRole('profesor')) {
            $estaAsignado = $user->materias()
                ->where('materias.id', $asistencia->clase->materia->id)
                ->exists();
            if (!$estaAsignado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para modificar esta asistencia.'
                ], 403);
            }
        }

        $asistencia->update($request->only(['condicion', 'observacion']));

        return response()->json([
            'success' => true,
            'message' => 'Asistencia actualizada correctamente.',
            'data' => $asistencia->load(['clase.materia', 'alumno'])
        ], 200);
    }


    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $asistencia = asistencias::findOrFail($id);
        if ($user->hasRole('profesor')) {
            $estaAsignado = $user->materias()
                ->where('materias.id', $asistencia->clase->materia->id)
                ->exists();
            if (!$estaAsignado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar esta asistencia.'
                ], 403);
            }
        }

        $asistencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asistencia eliminada correctamente.'
        ], 200);
    }

    public function estadisticasAlumno($user_id, $materia_id)
    {
        $user = request()->user();
        
        if ($user->hasRole('estudiante') && $user->id != $user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver estas estadísticas.'
            ], 403);
        }

        $materia = Materias::findOrFail($materia_id);
        $alumno = User::findOrFail($user_id);
        $totalClases = Clases::where('materias_id', $materia_id)->count();
        $asistencias = asistencias::whereHas('clase', function($q) use ($materia_id) {
            $q->where('materias_id', $materia_id);
        })->where('user_id', $user_id)->get();

        $presentes = $asistencias->where('condicion', 'presente')->count();
        $ausentes = $asistencias->where('condicion', 'ausente')->count();
        $justificados = $asistencias->where('condicion', 'justificado')->count();

        $porcentajeAsistencia = $totalClases > 0 
            ? round(($presentes / $totalClases) * 100, 2) 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'alumno' => [
                    'id' => $alumno->id,
                    'nombre_completo' => "{$alumno->userApellido}, {$alumno->userNombre}",
                    'documento' => $alumno->userDocumento
                ],
                'materia' => [
                    'id' => $materia->id,
                    'nombre' => $materia->matNombre
                ],
                'estadisticas' => [
                    'total_clases' => $totalClases,
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'justificados' => $justificados,
                    'porcentaje_asistencia' => $porcentajeAsistencia
                ]
            ]
        ], 200);
    }
}