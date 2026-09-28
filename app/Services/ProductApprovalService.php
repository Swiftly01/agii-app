<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Events\ProductApproved;
use App\Events\ProductRejected;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Single place that owns the product-approval state machine, mirroring
 * VendorApprovalService. Kept separate from VendorApprovalService because
 * the two lifecycles are independent (an approved vendor can still have
 * individual products rejected).
 */
class ProductApprovalService
{
    public function approve(Product $product, User $admin): Product
    {
        $product->forceFill([
            'status' => ProductStatus::Active->value,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'rejection_reason' => null,
        ])->save();

        ProductApproved::dispatch($product->fresh(), $admin);

        return $product->fresh();
    }

    public function reject(Product $product, User $admin, string $reason): Product
    {
        $product->forceFill([
            'status' => ProductStatus::Inactive->value,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'rejection_reason' => $reason,
        ])->save();

        ProductRejected::dispatch($product->fresh(), $reason, $admin);

        return $product->fresh();
    }

    /**
     * @param  array<int>  $productIds
     * @return Collection<int, Product>
     */
    public function bulkApprove(array $productIds, User $admin): Collection
    {
        return Product::whereIn('id', $productIds)->get()
            ->map(fn (Product $product) => $this->approve($product, $admin));
    }

    /**
     * @param  array<int>  $productIds
     * @return Collection<int, Product>
     */
    public function bulkReject(array $productIds, User $admin, string $reason): Collection
    {
        return Product::whereIn('id', $productIds)->get()
            ->map(fn (Product $product) => $this->reject($product, $admin, $reason));
    }
}
