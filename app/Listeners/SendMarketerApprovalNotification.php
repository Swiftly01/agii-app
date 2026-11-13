<?php

namespace App\Listeners;

use App\Events\MarketerApproved;
use App\Notifications\MarketerApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMarketerApprovalNotification implements ShouldQueue
{
    public function handle(MarketerApproved $event): void
    {
        $event->marketer->user->notify(new MarketerApprovedNotification());
    }
}
