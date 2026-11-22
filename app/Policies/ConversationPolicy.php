<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    // Only people in the thread can read it
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user);
    }

    // Only people in the thread can write to it
    public function send(User $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user);
    }
}
