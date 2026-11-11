<?php

namespace App\Services\Marketer;

use App\Models\User;

class ProductPostingPolicy
{
    
    public function canPostFor(User $vendor): bool
    {
        if (! $vendor->isVendorApproved()) {
            return false;
        }

        if (! config('marketer.require_vendor_subscription_to_post')) {
            return true;
        }

        return $vendor->hasActiveSubscription();
    }

    public function deniedMessage(User $vendor): string
    {
        if ($vendor->isVendorRejected()) {
            return 'This vendor application was rejected by an admin, so products cannot be posted for them.'
                .($vendor->vendor_rejection_reason ? ' Reason: '.$vendor->vendor_rejection_reason : '');
        }

        if (! $vendor->isVendorApproved()) {
            return 'This vendor is still awaiting admin approval. You can post products for them once they are approved.';
        }

        return 'This vendor needs an active subscription before products can be posted.';
    }
}
