<?php

namespace App\Listeners;

use App\Events\VendorSubscriptionActivated;
use App\Services\Marketer\ReferralCommissionService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

// Runs synchronously so the marketer sees the earning straight away, and only after the activation is committed
class CreateReferralCommission implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        private readonly ReferralCommissionService $commissions,
    ) {}

    public function handle(VendorSubscriptionActivated $event): void
    {
        $this->commissions->recordFor($event->subscription);
    }
}
