<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    //
    protected $table = 'conversations';
    protected $fillable = [
        'type' // 'privado', 'grupo'
    ]; 
    protected $guarded = [];


    public function messages():HasMany
    {
        return $this->hasMany(Message::class);
    } 

    public function participants():HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }
}
