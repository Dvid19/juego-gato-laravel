<?php

use App\Events\Mensaje;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {

    // Conversations
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);

    // Messages
    Route::get('/conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{id}/messages', [MessageController::class, 'store']);

    // Juego gato multijugador
    Route::get('/games/{code}', [GameController::class, 'game']);
    Route::post('/games', [GameController::class, 'create']);
    Route::post('/games/join/{code}', [GameController::class, 'join']);
    Route::post('/games/{id}/move', [GameController::class, 'move']);
    Route::post('/games/{id}/restart', [GameController::class, 'restart']);

});

Route::get('/prueba', function () {
    // event( new Mensaje(['user_id' => 1, 'message' => 'Hola soy el reverb']) );
    $conversation = Conversation::find(1)->participants->pluck('user_id')->contains(9);
    return response()->json(['conversation' => $conversation]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::post('/store', [UserController::class, 'store'])->middleware('auth:sanctum');

