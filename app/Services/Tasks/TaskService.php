<?php

namespace App\Services\Tasks;

use App\Enums\TaskStatus;
use App\Events\TaskAssigned;
use App\Events\TaskCompleted;
use App\Exceptions\InvalidTaskTransition;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

// Every task state change goes through here, controllers only translate HTTP
class TaskService
{
    public function __construct(private readonly DatabaseManager $db) {}

    // Assign several tasks to one staff member in a single transaction
    /** @param  array<int, AssignTaskData>  $tasks */
    public function assign(User $assigner, int $assigneeId, array $tasks): Collection
    {
        $created = $this->db->transaction(fn () => collect($tasks)->map(
            fn (AssignTaskData $data) => $this->createTask($assigner, $assigneeId, $data)
        ));

        // Events fire after the commit so listeners never see a rolled back task
        $created->each(fn (Task $task) => TaskAssigned::dispatch($task));

        return $created;
    }

    public function start(Task $task): Task
    {
        $current = $this->statusOf($task);

        if ($current === TaskStatus::InProgress) {
            return $task;
        }

        $this->guardTransition($current, TaskStatus::InProgress);

        $task->forceFill(['status' => TaskStatus::InProgress->value])->save();

        return $task;
    }

    public function complete(Task $task, User $actor, ?string $note = null): Task
    {
        $current = $this->statusOf($task);

        // Completing twice is a no-op so a double click never duplicates events
        if ($current === TaskStatus::Completed) {
            return $task;
        }

        $this->guardTransition($current, TaskStatus::Completed);

        $this->db->transaction(function () use ($task, $note) {
            $task->forceFill([
                'status' => TaskStatus::Completed->value,
                'progress' => 100,
                'completed_at' => now(),
            ])->save();

            if ($note) {
                $task->addStatusNote($note);
            }
        });

        TaskCompleted::dispatch($task, $actor);

        return $task;
    }

    // Progress drives the status, reaching 100 completes the task
    public function updateProgress(Task $task, User $actor, int $progress): Task
    {
        $current = $this->statusOf($task);

        if ($current === TaskStatus::Completed) {
            throw InvalidTaskTransition::between($current, TaskStatus::InProgress);
        }

        $state = Task::reconcile(null, $progress, $current->value);

        if ($state['status'] === TaskStatus::Completed->value) {
            return $this->complete($task, $actor);
        }

        $task->forceFill($state)->save();

        return $task;
    }

        public function updateDetails(Task $task, AssignTaskData $data): Task
    {
        $task->update([
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority->value,
            'deadline' => $data->deadline,
        ]);

        return $task;
    }

    // Soft delete, so the row stays recoverable
    public function delete(Task $task): void
    {
        $task->delete();
    }

    private function createTask(User $assigner, int $assigneeId, AssignTaskData $data): Task
    {
        return Task::create([
            'marketer_id' => $assigneeId,
            'assigned_by' => $assigner->id,
            'assignby_name' => $assigner->full_name,
            'report_to' => $assigner->full_name,
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority->value,
            'deadline' => $data->deadline,
            'status' => TaskStatus::Pending->value,
            'progress' => 0,
            'target_leads' => $data->targetLeads,
            'target_conversion' => $data->targetConversion,
        ]);
    }

    private function statusOf(Task $task): TaskStatus
    {
        return TaskStatus::from($task->status);
    }

    private function guardTransition(TaskStatus $from, TaskStatus $to): void
    {
        if (! $from->canTransitionTo($to)) {
            throw InvalidTaskTransition::between($from, $to);
        }
    }
}
