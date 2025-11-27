<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    //
    protected $table = 'messages';
    protected $fillable = [
        'conversation_id',
        'user_id',
        'content',
        'type', // 'texto', 'imagen', 'archivo'
        'send_at',
    ];
    protected $guarded = [];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation():BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function statuses():HasMany
    {
        return $this->hasMany(MessageStatus::class);
    }
}
