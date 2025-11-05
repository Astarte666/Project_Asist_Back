<?php

namespace App\Http\Controllers;

use App\Models\asistencias;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AsistenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $request->user();

        if ($user->hasRole('estudiante')) {
            $asistencias = asistencias::with(['clase', 'user'])
                ->where('user_id', $user->id)
                ->get();
        } else {
            $asistencias = asistencias::with(['clase', 'user'])->get();
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
        $user = $request->user();

        if (!$user->hasRole(['estudiante'])) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos para registrar asistencias.'
        ], 403);
    }
        try {
        $request->validate([
            'clase_id' => 'required|exists:clases,id',
            'user_id' => 'required|exists:users,id',
            'presente' => 'required|boolean',
            'observacion' => 'nullable|string|max:255',
        ]);

        $asistencia = asistencias::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Asistencia registrada correctamente.',
            'data' => $asistencia
        ], 201);

    } catch (ValidationException $e) {
        $errors = $e->errors();
        if (isset($errors['clase_id']) || isset($errors['user_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Materia o estudiante inexistente.',
                'errors' => $errors
            ], 404);
        }
        return response()->json([
            'success' => false,
            'message' => 'Datos inválidos.',
            'errors' => $errors
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error inesperado al registrar la asistencia.',
            'error' => $e->getMessage()
        ], 500);
    }
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asistencias = asistencias::findOrFail($id);
        $asistencias->update($request->all());
        return response()->json ($asistencias, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
    if (!$user->hasRole(['estudiante'])) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos para eliminar asistencias.'
        ], 403);
    }

    $asistencia = asistencias::find($id);
    if (!$asistencia) {
        return response()->json([
            'success' => false,
            'message' => 'Asistencia no encontrada.'
        ], 404);
    }
    $asistencia->delete();
    return response()->json([
        'success' => true,
        'message' => 'Asistencia eliminada correctamente.'
    ], 200);
    }
}
