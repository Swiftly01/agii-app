<?php

namespace App\Http\Requests\Tasks;

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Services\Tasks\AssignTaskData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOwnTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    // No assignee field, the task always belongs to the person creating it
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function taskData(): AssignTaskData
    {
        return AssignTaskData::fromArray($this->validated());
    }
}