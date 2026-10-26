@extends('layout.marketer')

@section('title', $task->title.' - Agii')
@section('page-title', 'Task')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.staff-tasks.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to staff tasks</a>

    @include('tasks.partials.flash')
    @include('tasks.partials.details', ['showAssignee' => true])
</div>
@endsection
