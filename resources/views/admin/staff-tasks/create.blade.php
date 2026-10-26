@extends('layout.marketer')

@section('title', 'Assign Tasks - Agii')
@section('page-title', 'Assign Tasks')

@section('content')
<div class="container-fluid">
    @include('tasks.partials.flash')

    <form method="POST" action="{{ route('admin.staff-tasks.store') }}" class="card">
        @csrf
        <div class="card-body">
            <div class="mb-4" style="max-width: 420px">
                <label class="form-label" for="assignee_id">Staff member</label>
                <select name="assignee_id" id="assignee_id" class="form-select" required>
                    <option value="">Select staff</option>
                    @foreach ($staff as $member)
                        <option value="{{ $member->id }}" @selected((int) old('assignee_id') === $member->id)>
                            {{ $member->first_name }} {{ $member->last_name }} ({{ $member->open_tasks_count }} open)
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="task-rows">
                @foreach (old('tasks', [[]]) as $i => $row)
                    @include('admin.staff-tasks.partials.task-row', ['i' => $i, 'row' => $row, 'priorities' => $priorities])
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm" id="add-row"><i class="fas fa-plus me-1"></i> Add another task</button>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary">Assign</button>
        </div>
    </form>

    <template id="task-row-template">
        @include('admin.staff-tasks.partials.task-row', ['i' => '__INDEX__', 'row' => [], 'priorities' => $priorities])
    </template>
</div>
@endsection

@push('scripts')
<script>
    // Clone the hidden row template with the next index
    (function () {
        const rows = document.getElementById('task-rows');
        const template = document.getElementById('task-row-template').innerHTML;
        let next = rows.children.length;

        document.getElementById('add-row').addEventListener('click', function () {
            rows.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', next++));
        });

        rows.addEventListener('click', function (event) {
            const remove = event.target.closest('.remove-row');
            if (remove && rows.children.length > 1) remove.closest('.task-row').remove();
        });
    })();
</script>
@endpush
