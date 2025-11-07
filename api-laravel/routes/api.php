<?php

use Illuminate\Https\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api;

// Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
//     return $request->$user();
// });

Route::get('/Arrecadacoes', [Arrecadacoes::class], index)->name('arrecadacoes.index');