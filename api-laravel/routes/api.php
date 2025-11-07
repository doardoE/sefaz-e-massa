<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ArrecadacoesController;

// Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
//     return $request->$user();
// });

Route::get('/arrecadacoes', [ArrecadacoesController::class, 'index']);
ROute::post('/arrecadacoes', [ArrecadacoesController::class, 'store']);
Route::put('/arrecadacoes/{id}', [ArrecadacoesController::class, 'update']);
Route::delete('/arrecadacoes/{id}', [ArrecadacoesController::class, 'destroy']);
Route::get('/arrecadacoes/{id}', [ArrecadacoesController::class, 'show']);
Route::get('arrecadacoes/kpis/dashboard', [ArrecadacoesController::class, 'dashboard']);
