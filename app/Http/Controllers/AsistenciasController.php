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
        return asistencias::with(['clase', 'user'])->get();
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
        $request-validate([
            'clase_id'=>'required|exists:clases,id',
            'user_id'=>'required|exists:user,id',
            'presente'=>'required|boolean',
            'observacion'=>'nullable|string|max:255',
        ]);

        $asistencias = asistencias::create($request->all());
        return response()->json($asistencias, 201);
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
    public function destroy($id)
    {
        $asistencias->destroy($id);
        return response()->json(['message' => 'Asistencia eliminada correctamente.']); 
    }
}
