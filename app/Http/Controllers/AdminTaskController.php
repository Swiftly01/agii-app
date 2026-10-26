<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Tasks\AssignTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\Tasks\TaskFilter;
use App\Services\Tasks\TaskListing;
use App\Services\Tasks\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTaskController extends Controller
{
    public function __construct(
        private readonly TaskService $tasks,
        private readonly TaskListing $listing,
    ) {}

    public function index(Request $request): View
    {
        $filter = TaskFilter::fromRequest($request);

        return view('admin.staff-tasks.index', [
            'tasks' => $this->listing->paginate($filter),
            'summary' => $this->listing->summary(),
            'filter' => $filter,
            'statuses' => TaskStatus::cases(),
            'priorities' => TaskPriority::cases(),
            'staff' => $this->staff(),
        ]);
    }

    public function create(): View
    {
        return view('admin.staff-tasks.create', [
            'priorities' => TaskPriority::cases(),
            'staff' => $this->staff(withOpenTaskCount: true),
        ]);
    }

    public function store(AssignTaskRequest $request): RedirectResponse
    {
        $created = $this->tasks->assign($request->user(), $request->assigneeId(), $request->taskData());

        return redirect()
            ->route('admin.staff-tasks.index')
            ->with('success', $created->count().' task(s) assigned.');
    }

    public function show(Task $task): View
    {
        return view('admin.staff-tasks.show', [
            'task' => $task->load(['marketer:id,first_name,last_name', 'assigner:id,first_name,last_name']),
        ]);
    }

    // Staff are users of type marketer, the same rule the assign request validates against
    private function staff(bool $withOpenTaskCount = false)
    {
        return User::query()
            ->where('user_type', 'marketer')
            ->select('id', 'first_name', 'last_name')
            ->when($withOpenTaskCount, fn ($q) => $q->withCount([
                'assignedTasks as open_tasks_count' => fn ($t) => $t->where('status', '!=', TaskStatus::Completed->value),
            ]))
            ->orderBy('first_name')
            ->get();
    }
}
