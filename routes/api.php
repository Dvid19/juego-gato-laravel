<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-chats', [MessageController::class, 'index'])->middleware('auth:sanctum');
Route::post('/sendMessage', [MessageController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::get('/prueba', [MessageController::class, 'prueba'])->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::post('/store', [UserController::class, 'store'])->middleware('auth:sanctum');

Route::post('/games', [GameController::class, 'create']);
Route::post('/games/join/{code}', [GameController::class, 'join']);
Route::post('/games/{id}/move', [GameController::class, 'move']);
Route::post('/games/{id}/restart', [GameController::class, 'restart']);
