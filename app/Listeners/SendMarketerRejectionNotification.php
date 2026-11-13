<?php

namespace App\Listeners;

use App\Events\MarketerRejected;
use App\Notifications\MarketerRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMarketerRejectionNotification implements ShouldQueue
{
    public function handle(MarketerRejected $event): void
    {
        $event->marketer->user->notify(new MarketerRejectedNotification($event->reason));
    }
}
