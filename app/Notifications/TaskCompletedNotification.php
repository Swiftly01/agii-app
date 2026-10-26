<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use App\Notifications\Support\NotificationPayload;
use Illuminate\Support\Str;

class TaskCompletedNotification extends AppNotification
{
    public function __construct(
        public readonly Task $task,
        public readonly User $completedBy,
    ) {}

    public function payload(object $notifiable): NotificationPayload
    {
        return new NotificationPayload(
            title: 'Task completed',
            body: "{$this->completedBy->first_name} completed: ".Str::limit($this->task->title, 80),
            url: route('admin.staff-tasks.show', $this->task),
            tag: "task-{$this->task->id}",
            data: ['type' => 'task_completed', 'task_id' => $this->task->id],
        );
    }
}
