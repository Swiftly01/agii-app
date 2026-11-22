<?php

namespace App\Services\Chat;

use App\Contracts\Chatable;
use App\Models\Conversation;
use App\Models\User;
use DomainException;
use Illuminate\Database\Eloquent\Model;

final class ListingConversationService
{
    // Finds or opens the buyer's conversation about a listing
    public function startFor(User $buyer, Model&Chatable $subject): Conversation
    {
        $seller = $subject->chatSeller();

        throw_if($buyer->is($seller), DomainException::class, 'You cannot message yourself about your own listing.');

        // createOrFirst is safe against double clicks thanks to the unique index
        $conversation = Conversation::createOrFirst([
            'type' => Conversation::TYPE_LISTING,
            'buyer_id' => $buyer->getKey(),
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
        ]);

        $conversation->participants()->syncWithoutDetaching([$buyer->getKey(), $seller->getKey()]);

        return $conversation;
    }
}
