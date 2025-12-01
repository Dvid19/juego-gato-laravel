<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Lista todas las conversaciones del usuario autenticado
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with([
            'participants.user:id,name',
            'messages' => fn ($q) => $q->latest()->limit(1)
        ])
        ->get();

        return response()->json($conversations);
    }

    /**
     * Crea una conversación (privada o grupo)
     * Request esperado:
     *  - type: privado | grupo
     *  - users: [1, 2, 3] 
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:privado,grupo',
            'users' => 'required|array|min:1'
        ]);

        // Crear conversación
        $conversation = Conversation::create([
            'type' => $request->type
        ]);

        // Agregar participante
        foreach($request->users as $userId){
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userId
            ]);
        }

        // Agregar al usuario final
        ConversationParticipant::firstOrCreate([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json($conversation->load('participants.user'), 201);
    }
}
