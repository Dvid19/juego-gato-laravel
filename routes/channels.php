<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('game.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    return ['id' => $user->id ?? null, 'name' => $user->name ?? null];
});

Broadcast::channel('chat.{id}', function($user, $id) {
    return (int) $user->id === (int) $id;
    // return ['id' => $user->id ?? null, 'name' => $user->name ?? null];
});