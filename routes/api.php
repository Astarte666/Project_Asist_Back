<?php

use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\MateriasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\InscripcionesController;



//REGISTRO
Route::post('/register', [AuthController::class, 'register']);

//LOGIN
Route::post('/login', [AuthController::class, 'login']);

//RUTAS PROTEGIDAS (Requires Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    //CARRERAS
    Route::middleware('role:administrador')->group(function () {
        Route::post('/carreras', [CarrerasController::class, 'store']);
        Route::get('/carreras/materias', [CarrerasController::class, 'showConMaterias']);
        Route::put('/carreras/{id}', [CarrerasController::class, 'update']);
        Route::delete('/carreras/{id}', [CarrerasController::class, 'destroy']);
    });
    Route::middleware('role:administrador|profesor|estudiante')->group(function () {
        Route::get('/carreras', [CarrerasController::class, 'index']);
        Route::get('/showConMaterias', [CarrerasController::class, 'showConMaterias']);
        Route::get('carreras/{id}/materias', [CarrerasController::class, 'showMaterias']);
    });

    //MATERIAS
    Route::middleware('role:administrador')->group(function () {
        Route::post('/materias', [MateriasController::class, 'store']);
        Route::put('/materias/{id}', [MateriasController::class, 'update']);
        Route::delete('/materias/{id}', [MateriasController::class, 'destroy']);
    });
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/materias', [MateriasController::class, 'index']);
        Route::get('showMateriasConCarreras', [MateriasController::class, 'showMateriasConCarreras']);
    });

    //INSCRIPCIONES
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/inscripciones', [InscripcionesController::class, 'index']);
        Route::post('inscripcion-materias', [InscripcionMateriaController::class, 'store']);
    });
    Route::middleware('role:administrador')->group(function () {
        Route::post('/inscripciones', [InscripcionesController::class, 'store']);
        Route::delete('/inscripciones/{id}', [InscripcionesController::class, 'destroy']);
    });

    //CLASES
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/clases', [ClasesController::class, 'index']);
    });
    Route::middleware('role:administrador')->group(function () {
        Route::post('/clases', [ClasesController::class, 'store']);
        Route::delete('/clases/{id}', [ClasesController::class, 'destroy']);
        Route::put('/clases/{id}', [ClasesController::class, 'update']);
    });

    //ASISTENCIAS
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/asistencias', [AsistenciasController::class, 'index']);
    });
    Route::middleware('role:administrador')->group(function () {
        Route::post('/asistencias', [AsistenciasController::class, 'store']);
        Route::delete('/asistencias/{id}', [AsistenciasController::class, 'destroy']);
        Route::put('/asistencias/{id}', [AsistenciasController::class, 'update']);
    });
});
