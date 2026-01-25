<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\DashboardController;

// LOGIN / REGISTRO
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // PACIENTES
    Route::get('/pacientes', [PacienteController::class, 'index']);
    Route::post('/pacientes', [PacienteController::class, 'store']);
    Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);

    // DOCTORES
    Route::get('/doctores', [DoctorController::class, 'index']);
    Route::post('/doctores', [DoctorController::class, 'store']);
    Route::put('/doctores/{id}', [DoctorController::class, 'update']);
    Route::delete('/doctores/{id}', [DoctorController::class, 'destroy']);

    // CITAS
    Route::get('/citas', [CitaController::class, 'index']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);

    // DASHBOARD
    Route::get('/dashboard-stats', [DashboardController::class, 'index']);
});
