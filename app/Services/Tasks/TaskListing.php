<?php

namespace App\Services\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

// Read side of tasks, one place for list and summary queries
class TaskListing
{
    private const PER_PAGE = 15;

    // Pass an assignee to scope the list to one staff member
    public function paginate(TaskFilter $filter, ?User $assignee = null): LengthAwarePaginator
    {
        return $filter->apply($this->scoped($assignee))
            ->with(['marketer:id,first_name,last_name,profile_image', 'assigner:id,first_name,last_name'])
            ->orderByRaw("status = 'completed'")
            ->orderBy('deadline')
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    // Counts per status plus overdue, two grouped queries however many tasks exist
    public function summary(?User $assignee = null): array
    {
        $byStatus = $this->scoped($assignee)
            ->toBase()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'pending' => (int) ($byStatus['pending'] ?? 0),
            'in_progress' => (int) ($byStatus['in_progress'] ?? 0),
            'completed' => (int) ($byStatus['completed'] ?? 0),
            'overdue' => $this->scoped($assignee)->overdue()->count(),
        ];
    }

    private function scoped(?User $assignee): Builder
    {
        return Task::query()->when($assignee, fn (Builder $q) => $q->assignedTo($assignee->id));
    }
}
