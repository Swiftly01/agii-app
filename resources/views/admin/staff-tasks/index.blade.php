@extends('layout.marketer')

@section('title', 'Staff Tasks - Agii')
@section('page-title', 'Staff Tasks')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.staff-tasks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Assign tasks</a>
    </div>

    @include('tasks.partials.flash')
    @include('tasks.partials.summary')
    @include('tasks.partials.filters', ['action' => route('admin.staff-tasks.index')])

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Assigned to</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th style="width:140px">Progress</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td><a href="{{ route('admin.staff-tasks.show', $task) }}" class="fw-semibold text-decoration-none">{{ $task->title }}</a></td>
                            <td>{{ $task->marketer?->first_name }} {{ $task->marketer?->last_name }}</td>
                            <td><span class="badge bg-{{ $task->priority_color }}">{{ ucfirst($task->priority) }}</span></td>
                            <td class="{{ $task->is_overdue ? 'text-danger fw-semibold' : '' }}">{{ $task->deadline?->format('d M Y') }}</td>
                            <td>
                                <div class="progress" style="height: 8px">
                                    <div class="progress-bar bg-{{ $task->status_color }}" style="width: {{ $task->progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $task->progress }}%</small>
                            </td>
                            <td><span class="badge bg-{{ $task->status_color }}">{{ \App\Enums\TaskStatus::tryFrom($task->status)?->label() }}</span></td>
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
