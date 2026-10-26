<?php

namespace App\Notifications;

use App\Models\Task;
use App\Notifications\Support\NotificationPayload;
use Illuminate\Support\Str;

class TaskAssignedNotification extends AppNotification
{
    public function __construct(public readonly Task $task) {}

    public function payload(object $notifiable): NotificationPayload
    {
        return new NotificationPayload(
            title: 'New task assigned',
            body: Str::limit($this->task->title, 90),
            url: route('staff.tasks.show', $this->task),
            tag: "task-{$this->task->id}",
            data: ['type' => 'task_assigned', 'task_id' => $this->task->id],
        );
    }
}
