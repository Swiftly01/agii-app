<?php

namespace App\Services\Marketer;

use App\Models\User;

class ProductPostingPolicy
{
    // Decides whether a marketer may post products for a vendor, driven by config so it can be switched on later
    public function canPostFor(User $vendor): bool
    {
        if (! config('marketer.require_vendor_subscription_to_post')) {
            return true;
        }

        return $vendor->hasActiveSubscription();
    }

    public function deniedMessage(): string
    {
        return 'This vendor needs an active subscription before products can be posted.';
    }
}
