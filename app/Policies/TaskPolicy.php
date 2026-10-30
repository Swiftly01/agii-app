<?php

namespace App\Policies;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    // Staff are users of type marketer, admins see everything
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->canWorkTasks();
    }

    public function view(User $user, Task $task): bool
    {
        return $user->isAdmin() || $user->canWorkTasks();
    }

    // Only admins hand out work
    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }

    // Start, progress and complete belong to the assignee, admins can step in
    public function update(User $user, Task $task): bool
    {
        return $user->isAdmin() || $this->isAssignee($user, $task);
    }

    private function isAssignee(User $user, Task $task): bool
    {
        return (int) $task->marketer_id === (int) $user->id;
    }

        // Staff and admins can create tasks, staff only ever for themselves
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMarketer();
    }

        // Staff may edit or delete only tasks they created for themselves and have not started
    public function manage(User $user, Task $task): bool
    {
        return $this->isAssignee($user, $task)
            && (int) $task->assigned_by === (int) $user->id
            && $task->status === TaskStatus::Pending->value;
    }
}
