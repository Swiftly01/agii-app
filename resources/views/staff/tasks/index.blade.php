@extends('layout.marketer')

@section('title', 'My Tasks - Agii')
@section('page-title', 'My Tasks')

@section('content')
<div class="container-fluid">
        <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('staff.tasks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New task</a>
    </div>
    @include('tasks.partials.flash')
    @include('tasks.partials.summary')
    @include('tasks.partials.filters', ['action' => route('staff.tasks.index')])

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th style="width:160px">Progress</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                <a href="{{ route('staff.tasks.show', $task) }}" class="fw-semibold text-decoration-none">{{ $task->title }}</a>
                                <div class="small text-muted">From {{ $task->assigner?->first_name }} {{ $task->assigner?->last_name }}</div>
                            </td>
                            <td><span class="badge bg-{{ $task->priority_color }}">{{ ucfirst($task->priority) }}</span></td>
                            <td class="{{ $task->is_overdue ? 'text-danger fw-semibold' : '' }}">{{ $task->deadline?->format('d M Y') }}</td>
                            <td>
                                <div class="progress" style="height: 8px">
                                    <div class="progress-bar bg-{{ $task->status_color }}" style="width: {{ $task->progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $task->progress }}%</small>
                            </td>
                            <td><span class="badge bg-{{ $task->status_color }}">{{ \App\Enums\TaskStatus::tryFrom($task->status)?->label() }}</span></td>
                            <td class="text-end">
                                @if ($task->status === 'pending')
                                    <form method="POST" action="{{ route('staff.tasks.start', $task) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary">Start</button>
                                    </form>
                                @endif
                                @if ($task->status !== 'completed')
                                    <form method="POST" action="{{ route('staff.tasks.complete', $task) }}" class="d-inline" onsubmit="return confirm('Mark this task as complete?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Complete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No tasks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $tasks->links() }}</div>
</div>
@endsection
