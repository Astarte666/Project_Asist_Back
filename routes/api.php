<?php

use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\MateriasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\InscripcionesController;

Route::get('/saludo', function (Request $request) {
    return response()->json(['mensaje' => 'Hola Mundo']);
});

//REGISTRO
Route::post('/register', [AuthController::class, 'register']);

//LOGIN
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

//INSCRIPCIONES
Route::get('/inscripciones', [InscripcionesController::class, 'index']);
Route::post('/inscripciones', [InscripcionesController::class, 'store']);
Route::delete('/inscripciones/{id}', [InscripcionesController::class, 'destroy']);
Route::put('/inscripciones/{id}', [InscripcionesController::class, 'update']);

//CLASES
Route::get('/clases', [ClasesController::class, 'index']);
Route::post('/clases', [ClasesController::class, 'store']);
Route::delete('/clases/{id}', [ClasesController::class, 'destroy']);
Route::put('/clases/{id}', [ClasesController::class, 'update']);

//ASISTENCIAS
Route::get('/asistencias', [AsistenciasController::class, 'index']);
Route::post('/asistencias', [AsistenciasController::class, 'store']);
Route::delete('/asistencias/{id}', [AsistenciasController::class, 'destroy']);
Route::put('/asistencias/{id}', [AsistenciasController::class, 'update']);
