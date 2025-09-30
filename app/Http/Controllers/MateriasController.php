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
        //
        $materias = Materias::all();
        return response()->json($materias);
    }

    public function showMateriasConCarreras() {
        $materias = Materias::with(relations: 'carreras')->get();
        return response()->json($materias);
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
        $request->validate([
            'matNombre' => 'required|string|max:255',
            'carreras_id' => 'required|exists:carreras,id',
        ]);

        $materias = Materias::create($request->all());
        return response()->json($materias, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Materias $materias)
    {
        //
        $materias->load('carreras'); 
        return response()->json($materias);
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
        //
        $request->validate([
            'matNombre' => 'sometimes|required|string|max:255',
            'carreras_id' => 'sometimes|required|exists:carreras,id',
        ]);

            $materias->update($request->all());
            return response()->json($materias);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materias $materias)
    {
        //
    }
}
