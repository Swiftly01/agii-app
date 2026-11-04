<?php

namespace App\Observers;

use App\Events\VendorSubscriptionActivated;
use App\Models\VendorSubscription;

// Single funnel for "a subscription went active", so checkout, webhook and marketer payments need no extra code
class VendorSubscriptionObserver
{
    // Never announce an activation from inside a transaction that may still roll back
    public $afterCommit = true;

    public function created(VendorSubscription $subscription): void
    {
        if ($subscription->status === VendorSubscription::STATUS_ACTIVE) {
            VendorSubscriptionActivated::dispatch($subscription);
        }
    }

    public function updated(VendorSubscription $subscription): void
    {
        if ($subscription->wasChanged('status') && $subscription->status === VendorSubscription::STATUS_ACTIVE) {
            VendorSubscriptionActivated::dispatch($subscription);
        }
    }
}
