<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Exceptions\InvalidTaskTransition;
use App\Http\Requests\Tasks\CompleteTaskRequest;
use App\Http\Requests\Tasks\StoreOwnTaskRequest;
use App\Http\Requests\Tasks\UpdateOwnTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskProgressRequest;
use App\Models\Task;
use App\Services\Tasks\TaskFilter;
use App\Services\Tasks\TaskListing;
use App\Services\Tasks\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StaffTaskController extends Controller
{
    public function __construct(
        private readonly TaskService $tasks,
        private readonly TaskListing $listing,
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Task::class);

        $filter = TaskFilter::fromRequest($request);

        return view('staff.tasks.index', [
            'tasks' => $this->listing->paginate($filter, $request->user()),
            'summary' => $this->listing->summary($request->user()),
            'filter' => $filter,
            'statuses' => TaskStatus::cases(),
            'priorities' => TaskPriority::cases(),
        ]);
    }

        public function create(): View
    {
        Gate::authorize('create', Task::class);

        return view('staff.tasks.create', ['priorities' => TaskPriority::cases()]);
    }

    public function store(StoreOwnTaskRequest $request): RedirectResponse
    {
        // Assigned by and assigned to are the same person
        $this->tasks->assign($request->user(), $request->user()->id, [$request->taskData()]);

        return redirect()->route('staff.tasks.index')->with('success', 'Task created.');
    }

    public function show(Task $task): View
    {
        Gate::authorize('view', $task);

        return view('staff.tasks.show', ['task' => $task->load('assigner:id,first_name,last_name')]);
    }

    public function start(Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        return $this->respond(fn () => $this->tasks->start($task), 'Task started.');
    }

    public function complete(CompleteTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        return $this->respond(
            fn () => $this->tasks->complete($task, $request->user(), $request->validated('note')),
            'Task marked as complete.',
        );
    }

    public function progress(UpdateTaskProgressRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        return $this->respond(
            fn () => $this->tasks->updateProgress($task, $request->user(), (int) $request->validated('progress')),
            'Progress updated.',
        );
    }

        public function edit(Task $task): View
    {
        Gate::authorize('manage', $task);

        return view('staff.tasks.edit', ['task' => $task, 'priorities' => TaskPriority::cases()]);
    }

    public function update(UpdateOwnTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('manage', $task);

        $this->tasks->updateDetails($task, $request->taskData());

        return redirect()->route('staff.tasks.show', $task)->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('manage', $task);

        $this->tasks->delete($task);

        return redirect()->route('staff.tasks.index')->with('success', 'Task deleted.');
    }

    // Turns a rejected transition into a friendly flash message
    private function respond(callable $action, string $success): RedirectResponse
    {
        try {
            $action();
        } catch (InvalidTaskTransition $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $success);
    }
}
