<?php

namespace App\Events;

use App\Models\VendorSubscription;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// A vendor subscription just became active, whoever paid for it and through whichever gateway flow
class VendorSubscriptionActivated
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly VendorSubscription $subscription) {}
}
