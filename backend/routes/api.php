<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EspecialidadController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\CitaController; // <-- ¡Asegúrate de importar este!

// --- Rutas Públicas ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Rutas Protegidas (Requieren Token) ---
Route::middleware('auth:sanctum')->group(function () {

    // Rutas de Autenticación
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);


    // --- CRUDs SOLO PARA ADMIN ---
    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('especialidades', EspecialidadController::class);
        Route::apiResource('doctores', DoctorController::class);
        Route::apiResource('pacientes', PacienteController::class);

        // Tu ruta DELETE está perfecta aquí:
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);
    });


    // GET (Todos los roles pueden ver)
    Route::get('/citas', [CitaController::class, 'index']);
    Route::get('/citas/{cita}', [CitaController::class, 'show']);

    // POST (Solo Paciente o Admin pueden crear)
    Route::post('/citas', [CitaController::class, 'store'])
         ->middleware('role:Paciente|Admin'); // El | significa "o"

    // PUT (Solo Doctor o Admin pueden actualizar)
    Route::put('/citas/{cita}', [CitaController::class, 'update'])
         ->middleware('role:Doctor|Admin');

});
