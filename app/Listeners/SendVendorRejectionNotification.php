<?php

namespace App\Listeners;

use App\Events\VendorRejected;
use App\Notifications\VendorRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVendorRejectionNotification implements ShouldQueue
{
    public function handle(VendorRejected $event): void
    {
        $event->vendor->notify(new VendorRejectedNotification($event->reason));
    }
}
