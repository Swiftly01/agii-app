{{-- Needs: $action, $submit, $priorities, $task (null when creating) --}}
<form method="POST" action="{{ $action }}" class="card" style="max-width: 720px">
    @csrf
    @if ($task)
        @method('PUT')
    @endif
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task?->title) }}" class="form-control" maxlength="255" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="priority">Priority</label>
                <select name="priority" id="priority" class="form-select" required>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->value }}" @selected(old('priority', $task?->priority ?? 'medium') === $priority->value)>{{ $priority->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="deadline">Deadline</label>
                <input type="datetime-local" name="deadline" id="deadline" value="{{ old('deadline', $task?->deadline?->format('Y-m-d\TH:i')) }}" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $task?->description) }}</textarea>
        </div>
    </div>
    <div class="card-footer text-end">
        <button class="btn btn-primary">{{ $submit }}</button>
    </div>
</form>