<?php

namespace App\Observers;

use App\Enums\ProductStatus;
use App\Events\ProductPublished;
use App\Models\Product;

/**
 * Single funnel for "a product went live". Products are activated from
 * several places (ProductApprovalService, MarketerController, marketer-created
 * listings); observing the model catches all of them without touching each.
 */
class ProductObserver
{
    /** Don't announce anything from inside a transaction that may roll back. */
    public $afterCommit = true;

    public function created(Product $product): void
    {
        // The products.status column defaults to 'active' when unset.
        if (($product->status ?? ProductStatus::Active->value) === ProductStatus::Active->value) {
            ProductPublished::dispatch($product);
        }
    }

    public function updated(Product $product): void
    {
        if ($product->wasChanged('status') && $product->status === ProductStatus::Active->value) {
            ProductPublished::dispatch($product);
        }
    }
}
