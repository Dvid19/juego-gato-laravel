<?php

namespace App\Http\Controllers;

use App\Events\Mensaje;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $usersChats = $user->chats; 
        return response()->json($usersChats, 200);
    }

    public function sendMessage(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'content' => 'required|string',
            // 'status' => 'required|string|in:enviado,entregado,leido',
            'from_user_id' => 'required|integer'
        ]);

        $message = Message::create([
            'to_user_id' => $user->id,
            'content' => $validated['content'],
            'from_user_id' => $validated['from_user_id']
        ]);

        $chatExists = $user->chats()->wherePivot('from_user_id', $validated['from_user_id'])->exists();
        
        if (!$chatExists) $user->chats()->attach($validated['from_user_id']);

        $data = ['message' => $message, 'user' => $request->user()];

        // broadcast( new Mensaje($data) )->toOthers();

        return response()->json($data, 201);
    }

    // 
    public function prueba(Request $request)
    {
        $user = $request->user();
        return response()->json(["message" => "Hola que hace", "user" => $user], 200);
    }
}
