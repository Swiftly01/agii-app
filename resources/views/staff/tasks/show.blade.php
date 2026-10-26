@extends('layout.marketer')

@section('title', $task->title.' - Agii')
@section('page-title', 'Task')

@section('content')
<div class="container-fluid">
    <a href="{{ route('staff.tasks.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to my tasks</a>

    @include('tasks.partials.flash')
    @include('tasks.partials.details')
        @can('manage', $task)
        <div class="mb-4 d-flex gap-2">
            <a href="{{ route('staff.tasks.edit', $task) }}" class="btn btn-outline-primary">Edit</a>
            <form method="POST" action="{{ route('staff.tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Delete</button>
            </form>
        </div>
    @endcan

    @if ($task->status !== 'completed')
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>Update progress</h6>
                        <form method="POST" action="{{ route('staff.tasks.progress', $task) }}" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="progress" min="0" max="100" value="{{ $task->progress }}" class="form-control" style="max-width:120px">
                            <button class="btn btn-primary">Save</button>
                        </form>
                        <small class="text-muted">Setting 100 completes the task.</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>Mark as complete</h6>
                        <form method="POST" action="{{ route('staff.tasks.complete', $task) }}">
                            @csrf
                            <textarea name="note" rows="2" maxlength="1000" class="form-control mb-2" placeholder="Optional completion note"></textarea>
                            <button class="btn btn-success">Complete task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
