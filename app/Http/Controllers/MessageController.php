<?php

namespace App\Http\Controllers;

use App\Events\Mensaje;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * Lista mensajes por conversación (con paginación)
     */
    public function index($id)
    {
        $conversation = Conversation::findOrFail($id);

        // Verificar si el usuario pertenece
        if (!$conversation->participants()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtener los mensajes
        $messages = Message::where('conversation_id', $id)
            ->with('user:id,name')
            ->orderBy('id', 'asc')
            ->paginate(30);

        return response()->json($messages, 200);
    }


    /**
     * Envia un mensaje
     * Request: 
     *  - content
     *  - type: texto|imagen|archivo 
     */
    public function store(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        // Verificar permiso
        if (!$conversation->participants()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json(['message' => 'No estas autorizado'], 403);
        }

        // Validacion de los datos enviados
        $request->validate([
            'content' => 'required',
            'type' => 'required|in:texto,imagen,archivo'
        ]);

        // Creación del mensaje
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'type' => $request->type 
        ]);

        // Crear el estado de cada mensaje
        foreach($conversation->participants() as $participant){
            MessageStatus::create([
                'message_id' => $message->id,
                'user_id' => $participant->user_id,
                'delivered_at' => now()
            ]);
        }

        // broadcast( new Mensaje($message) )->toOthers();
        event( new Mensaje($message) );

        return response()->json($message->load('user'), 201);
    }

}
