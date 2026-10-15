<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * A product just became visible to buyers (status -> active), regardless of
 * HOW it got there: admin approval, marketer approval, or a listing created
 * live. Dispatched by ProductObserver. May fire more than once per product
 * (re-approval after an edit); consumers must be idempotent.
 */
class ProductPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Product $product) {}
}
