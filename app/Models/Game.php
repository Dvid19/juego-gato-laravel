<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //
    protected $fillable = [
        'code', 'board', 'turn', 'player_x', 'player_o', 'winner', 'is_draw'
    ];

    protected $casts = [
        'board' => 'array',
        'is_drwa' => 'boolean'
    ];

    public static function booted()
    {
        static::creating(function ($game) {
            if(empty($game->board)) {
                $game->board = array_fill(0, 9, "");
            }
        });
    }
}
