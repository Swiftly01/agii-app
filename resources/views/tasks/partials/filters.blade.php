{{-- $action: route to submit to, $staff: optional list to filter by assignee --}}
<form method="GET" action="{{ $action }}" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="text" name="search" value="{{ $filter->search }}" class="form-control" placeholder="Search tasks">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected($filter->status === $status)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="priority" class="form-select">
            <option value="">All priorities</option>
            @foreach ($priorities as $priority)
                <option value="{{ $priority->value }}" @selected($filter->priority === $priority)>{{ $priority->label() }}</option>
            @endforeach
        </select>
    </div>
    @isset($staff)
        <div class="col-md-3">
            <select name="assignee" class="form-select">
                <option value="">All staff</option>
                @foreach ($staff as $member)
                    <option value="{{ $member->id }}" @selected($filter->assigneeId === $member->id)>{{ $member->first_name }} {{ $member->last_name }}</option>
                @endforeach
            </select>
        </div>
    @endisset
    <div class="col-auto d-flex align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="overdue" value="1" id="overdue" @checked($filter->overdue)>
            <label class="form-check-label" for="overdue">Overdue only</label>
        </div>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ $action }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
