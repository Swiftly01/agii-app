<?php

// app/Models/Conversation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['name', 'type'];
    
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function getOtherParticipantAttribute()
    {
        if ($this->type === 'private') {
            return $this->participants()->where('user_id', '!=', auth()->id())->first();
        }
        return null;
    }
}
