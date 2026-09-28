<?php

namespace App\Listeners;

use App\Events\ProductApproved;
use App\Notifications\ProductApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProductApprovalNotification implements ShouldQueue
{
    public function handle(ProductApproved $event): void
    {
        $event->product->user->notify(new ProductApprovedNotification($event->product));
    }
}
