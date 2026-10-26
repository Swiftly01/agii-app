<?php

namespace App\Services\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// Query string filters for the task lists, unknown values are ignored
final class TaskFilter
{
    public function __construct(
        public readonly ?TaskStatus $status = null,
        public readonly ?TaskPriority $priority = null,
        public readonly ?int $assigneeId = null,
        public readonly ?string $search = null,
        public readonly bool $overdue = false,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: TaskStatus::tryFrom((string) $request->query('status')),
            priority: TaskPriority::tryFrom((string) $request->query('priority')),
            assigneeId: $request->integer('assignee') ?: null,
            search: trim((string) $request->query('search')) ?: null,
            overdue: $request->boolean('overdue'),
        );
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status->value))
            ->when($this->priority, fn (Builder $q) => $q->where('priority', $this->priority->value))
            ->when($this->assigneeId, fn (Builder $q) => $q->where('marketer_id', $this->assigneeId))
            ->when($this->overdue, fn (Builder $q) => $q->overdue())
            ->when($this->search, fn (Builder $q) => $q->where(function (Builder $inner) {
                $inner->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }));
    }
}
