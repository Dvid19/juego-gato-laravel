<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prueba', function () {
    return "Hola wapo";
});

Route::post('/games', [GameController::class, 'create']);
Route::post('/games/join/{code}', [GameController::class, 'join']);
Route::post('/games/{id}/move', [GameController::class, 'move']);
Route::post('/games/{id}/restart', [GameController::class, 'restart']);
