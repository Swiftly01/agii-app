<?php

namespace App\Services\Spotlight;

use App\Enums\ProductStatus;
use App\Models\HeroSpotlight;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

// Owns the rules of the hero slot: who may show what, and for how long
final class SpotlightService
{
    // Products for the homepage carousel, random order so every paid vendor gets fair exposure
    public function slides(): Collection
    {
        return HeroSpotlight::live()
            ->with('product.category')
            ->inRandomOrder()
            ->limit((int) config('hero_spotlight.max_slides'))
            ->get()
            ->pluck('product');
    }

    // Live listings the vendor can pick from
    public function eligibleProducts(User $vendor): Collection
    {
        return $vendor->products()
            ->where('status', ProductStatus::Active->value)
            ->latest()
            ->get(['id', 'title']);
    }

    // A vendor may only spotlight one of their own live listings
    public function eligibleProduct(User $vendor, int $productId): Product
    {
        $product = $vendor->products()
            ->where('status', ProductStatus::Active->value)
            ->find($productId);

        throw_unless($product, ValidationException::withMessages([
            'product_id' => 'Choose one of your live listings.',
        ]));

        return $product;
    }

    // Swaps the showcased listing without charging again
    public function changeProduct(User $vendor, int $productId): HeroSpotlight
    {
        $spotlight = $vendor->heroSpotlight;

        throw_unless($spotlight?->isActive(), ValidationException::withMessages([
            'product_id' => 'You need an active spotlight before you can change the product.',
        ]));

        $spotlight->update(['product_id' => $this->eligibleProduct($vendor, $productId)->id]);

        return $spotlight;
    }

    // Adds one paid month, renewing early stacks on top of the time still left
    public function grantMonth(User $vendor, ?int $productId): HeroSpotlight
    {
        $spotlight = HeroSpotlight::where('user_id', $vendor->id)->lockForUpdate()->first()
            ?? new HeroSpotlight(['user_id' => $vendor->id]);

        $start = $spotlight->expires_at?->isFuture() ? $spotlight->expires_at : now();

        $spotlight->fill([
            'product_id' => $productId ?? $spotlight->product_id,
            'expires_at' => $start->copy()->addMonth(),
        ])->save();

        return $spotlight;
    }
}
