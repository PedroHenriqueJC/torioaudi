<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\EquipamentoController;

// Logged routes
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/me', [UsuarioController::class, 'me']);
    Route::post('/logout', [UsuarioController::class, 'logout']);

    Route::get('/salas', [SalaController::class, 'index']);
    Route::get('/salas/{id}', [SalaController::class], 'show');
    Route::get('/equipamento', [EquipamentoController::class], 'index');
    Route::get('/equipamento/{id}', [EquipamentoController::class], 'show');
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function (){
    Route::post('/salas', [SalaController::class, 'store']);
    Route::put('/salas/{id}', [SalaController::class], 'update');
    Route::delete('/salas/{id}', [SalaController::class], 'destroy');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    Route::post('/usuarios/{id}/restore', [UsuarioController::class, 'restore']);

    Route::post('/equipamento', [EquipamentoController::class], 'store');
    Route::put('/equipamento', [EquipamentoController::class], 'update');
    Route::delete('/equipamento', [EquipamentoController::class], 'destroy');
});

Route::post('/register', [UsuarioController::class, 'register']);
Route::post('/login', [UsuarioController::class, 'login']);


Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()
    ], 200);
});