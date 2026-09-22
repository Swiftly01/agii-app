<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;

class ProductBoostService
{
    public function boost(Product $product, User $vendor): void
    {
        $this->assertOwnership($product, $vendor);

        if ($product->isBoosted()) {
            return; // already boosted — nothing to do
        }

        $subscription = $vendor->activeBoostSubscription();

        if (! $subscription) {
            throw ValidationException::withMessages([
                'boost' => 'You need an active boost subscription to boost a product.',
            ]);
        }

        $limit = (int) config('boost.max_boosted_products');
        $currentlyBoosted = Product::where('user_id', $vendor->id)->boosted()->count();

        if ($currentlyBoosted >= $limit) {
            throw ValidationException::withMessages([
                'boost' => "You can only boost up to {$limit} products at a time. Unboost another product first.",
            ]);
        }

        $product->boost_expires_at = $subscription->expires_at;
        $product->save();
    }

    public function unboost(Product $product, User $vendor): void
    {
        $this->assertOwnership($product, $vendor);

        $product->boost_expires_at = null;
        $product->is_boost_carousel_pick = false;
        $product->save();
    }

    public function setCarouselPick(Product $product, User $vendor): void
    {
        $this->assertOwnership($product, $vendor);

        if (! $product->isBoosted()) {
            throw ValidationException::withMessages([
                'boost' => 'Only a currently boosted product can be set as the carousel feature.',
            ]);
        }

        // Exactly one carousel pick per vendor at a time.
        Product::where('user_id', $vendor->id)
            ->where('id', '!=', $product->id)
            ->update(['is_boost_carousel_pick' => false]);

        $product->is_boost_carousel_pick = true;
        $product->save();
    }

    
    public function syncBoostedProductsExpiry(User $vendor, CarbonInterface $newExpiry): void
    {
        Product::where('user_id', $vendor->id)
            ->boosted()
            ->update(['boost_expires_at' => $newExpiry]);
    }

    public function boostUsage(User $vendor): array
    {
        return [
            'used' => Product::where('user_id', $vendor->id)->boosted()->count(),
            'limit' => (int) config('boost.max_boosted_products'),
        ];
    }

    private function assertOwnership(Product $product, User $vendor): void
    {
        if ($product->user_id !== $vendor->id) {
            abort(403, 'You can only boost your own products.');
        }
    }
}
