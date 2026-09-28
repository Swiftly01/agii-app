<?php

namespace App\Events;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly string $reason,
        public readonly ?User $rejectedBy = null,
    ) {}
}
