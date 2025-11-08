<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ArrecadacoesController;

// Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
//     return $request->$user();
// });

Route::get('arrecadacoes/kpis', [ArrecadacoesController::class, 'kpis']);
Route::get('arrecadacoes/dashboard', [ArrecadacoesController::class, 'dashboard']);
Route::get('/arrecadacoes', [ArrecadacoesController::class, 'index']);
ROute::post('/arrecadacoes', [ArrecadacoesController::class, 'store']);
Route::put('/arrecadacoes/{arrecadacoes}', [ArrecadacoesController::class, 'update']);
Route::delete('/arrecadacoes/{arrecadacoes}', [ArrecadacoesController::class, 'destroy']);
Route::get('/arrecadacoes/{arrecadacoes}', [ArrecadacoesController::class, 'show']);

