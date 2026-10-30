@extends(auth()->user()->isStaff() ? 'layout.staff' : 'layout.marketer')

@section('title', 'Edit Task - Agii')
@section('page-title', 'Edit Task')

@section('content')
<div class="container-fluid">
    <a href="{{ route('staff.tasks.show', $task) }}" class="btn btn-link px-0 mb-3">&larr; Back to task</a>

    @include('tasks.partials.flash')

    @include('staff.tasks.partials.form', [
        'action' => route('staff.tasks.update', $task),
        'submit' => 'Save changes',
        'task' => $task,
        'priorities' => $priorities,
    ])
</div>
@endsection