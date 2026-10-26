<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

// Auto-discovered from the typed handle(), do not register it in AppServiceProvider
class NotifyAssignerOfCompletedTask implements ShouldQueue
{
    public function handle(TaskCompleted $event): void
    {
        $assigner = $event->task->assigner;

        // An admin completing their own assigned task does not need a ping
        if (! $assigner || $assigner->is($event->completedBy)) {
            return;
        }

        $assigner->notify(new TaskCompletedNotification($event->task, $event->completedBy));
    }
}
