{{-- Read-only task details shared by the staff and admin show pages --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h4 class="mb-0">{{ $task->title }}</h4>
            <div>
                <span class="badge bg-{{ $task->priority_color }}">{{ ucfirst($task->priority) }}</span>
                <span class="badge bg-{{ $task->status_color }}">{{ \App\Enums\TaskStatus::tryFrom($task->status)?->label() }}</span>
            </div>
        </div>

        <p class="text-muted" style="white-space: pre-line">{{ $task->description ?: 'No description.' }}</p>

        <dl class="row mb-3">
            <dt class="col-sm-3">Assigned by</dt>
            <dd class="col-sm-9">{{ $task->assigner?->first_name }} {{ $task->assigner?->last_name }}</dd>
            @isset($showAssignee)
                <dt class="col-sm-3">Assigned to</dt>
                <dd class="col-sm-9">{{ $task->marketer?->first_name }} {{ $task->marketer?->last_name }}</dd>
            @endisset
            <dt class="col-sm-3">Deadline</dt>
            <dd class="col-sm-9 {{ $task->is_overdue ? 'text-danger' : '' }}">{{ $task->deadline?->format('d M Y, H:i') }}</dd>
            @if ($task->completed_at)
                <dt class="col-sm-3">Completed</dt>
                <dd class="col-sm-9">{{ $task->completed_at->format('d M Y, H:i') }}</dd>
            @endif
        </dl>

        <div class="progress mb-3" style="height: 10px">
            <div class="progress-bar bg-{{ $task->status_color }}" style="width: {{ $task->progress }}%"></div>
        </div>
        <small class="text-muted">{{ $task->progress }}% complete</small>

        @if ($task->notes)
            <hr>
            <h6>Notes</h6>
            <p class="small text-muted" style="white-space: pre-line">{{ $task->notes }}</p>
        @endif
    </div>
</div>
