<?php

use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\MateriasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/saludo', function (Request $request) {
    return response()->json(['mensaje' => 'Hola Mundo']);
});

//login/registro
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//CARRERAS
Route::get('/carreras', [CarrerasController::class, 'index']);
Route::get('/showConMaterias', [CarrerasController::class, 'showConMaterias']);
Route::post('/carreras', [CarrerasController::class, 'store']);
Route::put('/carreras/{id}', [CarrerasController::class, 'update']);
Route::delete('/carreras/{id}', [CarrerasController::class, 'destroy']);


//MATERIAS
Route::get('/materias', [MateriasController::class, 'index']);
Route::get('showMateriasConCarreras', [MateriasController::class, 'showMateriasConCarreras']);
Route::post('/materias', [MateriasController::class, 'store']);
Route::put('/materias/{}', [MateriasController::class, 'update']);
Route::delete('/materias/{id}', [MateriasController::class, 'destroy']);
