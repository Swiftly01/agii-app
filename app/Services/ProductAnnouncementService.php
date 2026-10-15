<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Jobs\SendProductNotificationChunk;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Announces a newly live product to buyers.
 *
 * Scaling model: this class only claims the product and slices the audience
 * into id chunks (cheap, id-only queries). Each chunk becomes one queued job,
 * which in turn queues one notification job per user — so work is spread
 * across as many workers as you run, and memory stays flat at any audience size.
 */
class ProductAnnouncementService
{
    public function announce(Product $product): void
    {
        if (! $this->claim($product)) {
            return;
        }

        $this->audience($product)
            ->select('id')
            ->chunkById(
                (int) config('notifications.chunk_size'),
                fn (Collection $buyers) => SendProductNotificationChunk::dispatch($product, $buyers->modelKeys()),
            );
    }

    /**
     * Who hears about this product. This is THE place to add targeting later
     * (category interests, location, followed stores) — nothing else changes.
     *
     * Today: buyers who enabled push, excluding the seller.
     *
     * @return Builder<User>
     */
    protected function audience(Product $product): Builder
    {
        return User::query()
            ->where('user_type', 'customer')
            ->whereKeyNot($product->user_id)
            ->whereHas('pushSubscriptions');
    }

    /**
     * Atomic "first one wins" guard: a single UPDATE decides which caller
     * announces, so duplicate events, double-registered listeners, retries
     * and re-approvals can never notify buyers twice.
     */
    protected function claim(Product $product): bool
    {
        return Product::query()
            ->whereKey($product->getKey())
            ->where('status', ProductStatus::Active->value)
            ->whereNull('buyers_notified_at')
            ->update(['buyers_notified_at' => now()]) === 1;
    }
}
