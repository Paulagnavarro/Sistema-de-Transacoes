<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TransacaoController;

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas: só admin logado (com token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('transacoes', TransacaoController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
