<?php

use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\MateriasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\InscripcionMateriaController;
use App\Http\Controllers\UserController;



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

    //USER 
    Route::middleware('role:estudiante|profesor|administrador')->group(function () {
        Route::get('/userCarrerasMaterias', [UserController::class, 'userCarrerasMaterias']);
    });
    
    //CARRERAS
    Route::middleware('role:administrador')->group(function () {
        Route::post('/carreras', [CarrerasController::class, 'store']);
        Route::get('/carreras/{id}', [CarrerasController::class, 'show']);
        Route::get('/carreras/materias', [CarrerasController::class, 'showConMaterias']);
        Route::get('/carreras/{id}/curso', [CarrerasController::class, 'showCurso']);
        Route::get('/carreras/select', [CarrerasController::class, 'listaSelect']);
        Route::get('/carreras/{carrera_id}/estudiantes', [CarrerasController::class, 'estudiantesInscriptos']);
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
        Route::get('/materias/{id}/estudiantes', [MateriasController::class, 'estudiantesInscriptos']);
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
        Route::get('/mis-inscripciones', [InscripcionesController::class, 'misInscripciones']);
        Route::post('inscripcion-materias', [InscripcionMateriaController::class, 'store']);
        Route::post('/inscripciones', [InscripcionesController::class, 'store']);
        Route::delete('/desinscribir/{materia_id}', [InscripcionesController::class, 'desinscribir']);

    });
    Route::middleware('role:administrador')->group(function () {
        Route::delete('/inscripciones/{id}', [InscripcionesController::class, 'destroy']);
        Route::get('/inscripciones/{id}', [InscripcionesController::class, 'show']);
    });

    //CLASES
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/clases', [ClasesController::class, 'index']);
    });
    Route::middleware('role:administrador')->group(function () {
        Route::get('/clases/{id}', [ClasesController::class, 'show']);
        Route::post('/clases', [ClasesController::class, 'store']);
        Route::delete('/clases/{id}', [ClasesController::class, 'destroy']);
        Route::put('/clases/{id}', [ClasesController::class, 'update']);
    });

    //ASISTENCIAS
    Route::middleware('role:administrador|estudiante|profesor')->group(function () {
        Route::get('/asistencias', [AsistenciasController::class, 'index']);
        Route::get('/asistencias/estadisticas/{user_id}/{materia_id}', [AsistenciasController::class, 'estadisticasAlumno']);
        Route::get('/asistencias/estadisticas-generales/{user_id}', [AsistenciasController::class, 'estadisticasGeneralesAlumno']);
        Route::get('/asistencias/estadisticas-materia/{materia_id}', [AsistenciasController::class, 'estadisticasMateria']);
    });
    Route::middleware('role:administrador')->group(function () {
        Route::get('/asistencias/clase/{clase_id}', [AsistenciasController::class, 'prepararTomarAsistencia']);
        Route::post('/asistencias/clase/{clase_id}', [AsistenciasController::class, 'guardarAsistencias']);
        Route::put('/asistencias/{id}', [AsistenciasController::class, 'update']);
        Route::delete('/asistencias/{id}', [AsistenciasController::class, 'destroy']);

        
    });

    //GESTIÓN DE REGISTRO
    Route::middleware(['role:administrador'])->group(function () {
    Route::post('/gestion/usuarios/{id}/aceptar', [AuthController::class, 'aceptarUsuario']);
    });
    Route::get('/gestion/usuarios-pendientes', [AuthController::class, 'usuariosPendientes']);
});
