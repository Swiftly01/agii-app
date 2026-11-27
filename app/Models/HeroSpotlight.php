<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// A vendor's single paid slot in the homepage hero carousel
class HeroSpotlight extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Paid up and pointing at a listing buyers can actually open
    public function scopeLive(Builder $query): Builder
    {
        return $query
            ->where('expires_at', '>', now())
            ->whereHas('product', fn (Builder $product) => $product->where('status', ProductStatus::Active->value));
    }

    public function isActive(): bool
    {
        return $this->expires_at->isFuture();
    }
}
