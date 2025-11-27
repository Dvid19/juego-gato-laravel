<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    //
    protected $table = 'messages_status';
    protected $fillable = [
        'message_id',
        'user_id',
        'delivered_at',
        'allowed_at',
    ];
    protected $guarded = [];
}
