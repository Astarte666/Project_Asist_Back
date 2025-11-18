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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('estudiante')) {
            $asistencias = asistencias::with(['clase.materia', 'alumno'])
                ->where('user_id', $user->id)
                ->get();
        } else {
            // Para admin/profesor: todas, o filtrar por materias
            $asistencias = asistencias::with(['clase.materia', 'alumno'])->get();
        }

        return response()->json([
            'success' => true,
            'data' => $asistencias
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(asistencias $asistencias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(asistencias $asistencias)
    {
        //
    }

    public function prepararTomarAsistencia($clase_id)
    {
        $user = request()->user();
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $clase = Clases::with('materias')->findOrFail($clase_id);
        $alumnos = $clase->materias->estudiantes()
            ->select('users.id', 'userNombre', 'userApellido', 'userDocumento')
            ->orderBy('userApellido')
            ->get();

        if ($alumnos->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No hay alumnos inscriptos en esta materia.',
                'data' => ['clase' => $clase, 'alumnos' => []]
            ], 200);
        }

        // Asistencias previas (para edición)
        $asistenciasPrevias = asistencias::where('clase_id', $clase_id)
            ->get()
            ->keyBy('user_id')
            ->map(fn($a) => ['presente' => $a->presente, 'observacion' => $a->observacion]);

        return response()->json([
            'success' => true,
            'message' => 'Listado de alumnos obtenido correctamente.',
            'data' => [
                'clase' => $clase,
                'alumnos' => $alumnos,
                'asistencias_previas' => $asistenciasPrevias
            ]
        ], 200);
    }

    public function guardarAsistencias(Request $request, $clase_id)
    {
        $user = $request->user();
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }
        $clase = Clases::findOrFail($clase_id);
        $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*.user_id' => 'required|exists:users,id',
            'asistencias.*.presente' => 'required|boolean',
            'asistencias.*.observacion' => 'nullable|string|max:255',
        ]);

        $alumnosInscriptos = $clase->materia->estudiantes()->pluck('users.id')->toArray();
        foreach ($request->asistencias as $item) {
            if (!in_array($item['user_id'], $alumnosInscriptos)) {
                return response()->json(['success' => false, 'message' => 'Alumno no inscripto en la materia.'], 403);
            }
        }
        try {
            DB::transaction(function () use ($request, $clase_id) {
                asistencias::where('clase_id', $clase_id)->delete();
                foreach ($request->asistencias as $item) {
                    asistencias::create([
                        'clase_id' => $clase_id,
                        'user_id' => $item['user_id'],
                        'presente' => $item['presente'],
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }
        $request->validate([
            'presente' => 'boolean',
            'observacion' => 'nullable|string|max:255',
        ]);
        $asistencia = asistencias::findOrFail($id);
        $asistencia->update($request->only(['presente', 'observacion']));
        return response()->json([
            'success' => true,
            'data' => $asistencia
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole(['profesor', 'administrador'])) {
            throw UnauthorizedException::forRoles(['profesor', 'administrador']);
        }

        $asistencia = asistencias::findOrFail($id);
        $asistencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asistencia eliminada correctamente.'
        ], 200);
    }
}
