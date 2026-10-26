<?php

namespace App\Models;

use App\Enums\AnalyticsEventType;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['type', 'user_id', 'user_type', 'subject_type', 'subject_id', 'session_id', 'created_at'];

    protected $casts = [
        'type' => AnalyticsEventType::class,
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeOfType(Builder $query, AnalyticsEventType $type): Builder
    {
        return $query->where('type', $type->value);
    }

    public function scopeSince(Builder $query, CarbonImmutable $from): Builder
    {
        return $query->where('created_at', '>=', $from);
    }
}
