<?php

namespace App\Http\Controllers;

use App\Events\Mensaje;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'gt:1'],
            'content' => ['required', 'string'],
            'to_user_id' => ['required', 'integer', 'gt:1']
        ]);
        
        $message = Message::create([
            "user_id" => $validated["user_id"],
            "content" => $validated["content"],
            "to_user_id" => $validated["to_user_id"],
        ]);

        $messageComplete = ["user" => $request->user(), "message" => $message];

        // broadcast(new Mensaje($messageComplete))->toOthers();

        return response()->json($messageComplete, 200);
    }

    // 
    public function prueba(Request $request)
    {
        $user = $request->user();
        return response()->json(["message" => "Hola que hace", "user" => $user], 200);
    }
}
