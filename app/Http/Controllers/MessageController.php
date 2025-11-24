<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    //
    public function sendMessage(Request $request)
    {

    }

    public function prueba(Request $request)
    {
        $user = $request->user();
        return response()->json(["message" => "Hola que hace", "user" => $user], 200);
    }
}
