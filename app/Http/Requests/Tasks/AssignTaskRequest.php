<?php

namespace App\Http\Requests\Tasks;

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Services\Tasks\AssignTaskData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', Task::class);
    }

    public function rules(): array
    {
        return [
            // Only staff accounts can receive tasks
            'assignee_id' => ['required', 'integer', Rule::exists('users', 'id')->where('user_type', 'marketer')],
            'tasks' => ['required', 'array', 'min:1', 'max:20'],
            'tasks.*.title' => ['required', 'string', 'max:255'],
            'tasks.*.description' => ['nullable', 'string', 'max:5000'],
            'tasks.*.priority' => ['required', Rule::enum(TaskPriority::class)],
            'tasks.*.deadline' => ['required', 'date', 'after_or_equal:today'],
            'tasks.*.target_leads' => ['nullable', 'integer', 'min:0'],
            'tasks.*.target_conversion' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function assigneeId(): int
    {
        return (int) $this->validated('assignee_id');
    }

    /** @return array<int, AssignTaskData> */
    public function taskData(): array
    {
        return array_map(AssignTaskData::fromArray(...), array_values($this->validated('tasks')));
    }
}
