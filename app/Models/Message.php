<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'to_user_id',
        'content',
        'status',
        'from_user_id'
    ];
}
