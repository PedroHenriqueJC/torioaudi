<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controller\EquipamentoSalaController;
// Logged routes
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/me', [UsuarioController::class, 'me']); // Documentada
    Route::post('/logout', [UsuarioController::class, 'logout']); // Documentada

    Route::get('/salas', [SalaController::class, 'index']); // Documentada
    Route::get('/salas/{id}', [SalaController::class, 'show']); // Documentada
    Route::get('/equipamento', [EquipamentoController::class, 'index']); // Documentada
    Route::get('/equipamento/{id}', [EquipamentoController::class, 'show']); // Documentada
    Route::prefix('sala-equipamento')->group(function (){
        Route::get('sala/{salaId}', [EquipamentoSalaController::class, 'showBySala']); // Documentada
        Route::get('equipamento/{equipamentoId}', [EquipamentoSalaController::class, 'showByEquipamento']); // Documentada
    });
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function (){
    Route::post('/salas', [SalaController::class, 'store']); // Documentada
    Route::put('/salas/{id}', [SalaController::class, 'update']); // Documentada
    Route::delete('/salas/{id}', [SalaController::class, 'destroy']); // Documentada
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']); // Documentada
    Route::post('/usuarios/{id}/restore', [UsuarioController::class, 'restore']); // Documentada
    Route::get('/usuarios', [UsuarioController::class, 'index']); // Documentada


    Route::post('/equipamento', [EquipamentoController::class, 'store']); // Documentada
    Route::put('/equipamento/{id}', [EquipamentoController::class, 'update']); // Documentada
    Route::delete('/equipamento/{id}', [EquipamentoController::class, 'destroy']); // Documentada

    Route::prefix('sala-equipamento')->group(function (){
        Route::post('', [EquipamentoSalaController::class, 'store']); // Documentada
        Route::put('{salaId}/{equipamentoId}', [EquipamentoSalaController::class, 'update']); // Documentada 
        Route::delete('{salaId}/{equipamentoId}', [EquipamentoSalaController::class, 'destroy']); // Documentada
    });
});

Route::post('/register', [UsuarioController::class, 'register']); // Documentada
Route::post('/login', [UsuarioController::class, 'login']); // Documentada


Route::get('/health', function () { // Documentada
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()
    ], 200);
});