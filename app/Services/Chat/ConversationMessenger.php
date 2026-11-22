<?php

namespace App\Services\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

final class ConversationMessenger
{
    // Stores a message and bumps the thread so the inbox re-sorts
    public function send(Conversation $conversation, User $sender, string $body): Message
    {
        $message = $conversation->messages()->create([
            'user_id' => $sender->getKey(),
            'body' => trim($body),
        ]);

        $conversation->touch();

        return $message->setRelation('user', $sender);
    }

    // Messages newer than the given id, oldest first
    public function since(Conversation $conversation, int $afterId = 0): Collection
    {
        return $conversation->messages()
            ->with('user')
            ->where('id', '>', $afterId)
            ->oldest('id')
            ->get();
    }

    // Marks everything the other side sent as read
    public function markRead(Conversation $conversation, User $viewer): void
    {
        $conversation->messages()
            ->where('user_id', '!=', $viewer->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

        // Unread listing messages across every thread the user is in
    public function unreadCountFor(User $user): int
    {
        return Message::query()
            ->where('user_id', '!=', $user->getKey())
            ->whereNull('read_at')
            ->whereHas('conversation', fn ($conversation) => $conversation
                ->listing()
                ->whereHas('participants', fn ($participants) => $participants->whereKey($user->getKey())))
            ->count();
    }
}
