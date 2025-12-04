<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('game.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    return ['id' => $user->id ?? null, 'name' => $user->name ?? null];
});

Broadcast::channel('chat.{chatId}', function(User $user, int $chatId) {
    return Conversation::find($chatId)->participants->pluck('user_id')->contains($user->id);
});



// return (int) $user->id === Conversation::find(1)->participants->pluck('user_id')->contains(9);
    // return ['id' => $user->id ?? null, 'name' => $user->name ?? null];
    // return Conversation::find($chatId)->participants->pluck('user_id')->contains($user->id);