<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationParticipant extends Model
{
    //
    protected $table = 'conversations_participants';
    protected $fillable = [
        'conversation_id',
        'user_id',
        'role', // 'miembro', 'admin'
        'joined_at',
    ];
    protected $guarded = [];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
