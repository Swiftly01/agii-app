<?php

namespace App\Listeners;

use App\Events\ProductRejected;
use App\Notifications\ProductRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProductRejectionNotification implements ShouldQueue
{
    public function handle(ProductRejected $event): void
    {
        $event->product->user->notify(new ProductRejectedNotification($event->product, $event->reason));
    }
}
