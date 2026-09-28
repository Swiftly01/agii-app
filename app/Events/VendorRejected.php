<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly User $vendor,
        public readonly string $reason,
        public readonly ?User $rejectedBy = null,
    ) {}
}
