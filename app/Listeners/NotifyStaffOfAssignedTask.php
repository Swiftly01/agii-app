<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

// Auto-discovered from the typed handle(), do not register it in AppServiceProvider
class NotifyStaffOfAssignedTask implements ShouldQueue
{
      public function handle(TaskAssigned $event): void
    {
        $task = $event->task;

        // Nobody needs a ping about a task they created for themselves
        if ((int) $task->assigned_by === (int) $task->marketer_id) {
            return;
        }

        $task->marketer?->notify(new TaskAssignedNotification($task));
    }
}
