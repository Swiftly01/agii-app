<?php

// app/Models/Conversation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Conversation extends Model
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_LISTING = 'listing';

    protected $fillable = ['name', 'type', 'subject_type', 'subject_id', 'buyer_id'];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // The listing this conversation is about
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function getOtherParticipantAttribute()
    {
        if ($this->type !== self::TYPE_GROUP) {
            return $this->participants()->where('user_id', '!=', auth()->id())->first();
        }
        return null;
    }

    // The other side of the thread, read from the eager-loaded participants
    public function counterpartFor(User $viewer): ?User
    {
        return $this->participants->firstWhere('id', '!=', $viewer->id);
    }

    public function isParticipant(User $user): bool
    {
        return $this->participants()->whereKey($user->getKey())->exists();
    }

    public function scopeListing(Builder $query): Builder
    {
        return $query->where('conversations.type', self::TYPE_LISTING);
    }

    // Adds unread_count: messages from the other side the viewer has not opened yet
    public function scopeWithUnreadFor(Builder $query, User $viewer): Builder
    {
        return $query->withCount(['messages as unread_count' => fn (Builder $messages) => $messages
            ->where('user_id', '!=', $viewer->getKey())
            ->whereNull('read_at')]);
    }
}
