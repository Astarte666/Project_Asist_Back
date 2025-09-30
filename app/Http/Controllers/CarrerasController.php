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
        //
        $carreras = Carreras::all();
        return response()->json($carreras);
    }

    public function showConMaterias() {
        
        return Carreras::with('materias')->get();
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
            'carreNombre' => 'required|string|max:255|unique:carreras,carreNombre',
        ]);
        $carreras = Carreras::create($request->all());
        return response()->json($carreras, 201);
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
       $myCarrera = Carreras::find($id);
        $myCarrera -> carreNombre = $request->carreNombre;
        $myCarrera->save();
        return response()->json($myCarrera);
                
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $myCarrera = Carreras::destroy($id);
        return response()->json($myCarrera, 200);
    }
}
