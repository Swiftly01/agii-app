@extends(auth()->user()->isStaff() ? 'layout.staff' : 'layout.marketer')

@section('title', 'New Task - Agii')
@section('page-title', 'New Task')

@section('content')
<div class="container-fluid">
    <a href="{{ route('staff.tasks.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to my tasks</a>

    @include('tasks.partials.flash')

    @include('staff.tasks.partials.form', [
    'action' => route('staff.tasks.store'),
    'submit' => 'Create task',
    'task' => null,
    'priorities' => $priorities,
    ])
</div>
@endsection