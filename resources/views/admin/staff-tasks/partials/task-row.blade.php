<div class="task-row border rounded p-3 mb-3">
    <div class="row g-2">
        <div class="col-md-6">
            <input type="text" name="tasks[{{ $i }}][title]" value="{{ $row['title'] ?? '' }}" class="form-control" placeholder="Title" required maxlength="255">
        </div>
        <div class="col-md-3">
            <select name="tasks[{{ $i }}][priority]" class="form-select" required>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" @selected(($row['priority'] ?? 'medium') === $priority->value)>{{ $priority->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="datetime-local" name="tasks[{{ $i }}][deadline]" value="{{ $row['deadline'] ?? '' }}" class="form-control" required>
        </div>
        <div class="col-12">
            <textarea name="tasks[{{ $i }}][description]" rows="2" class="form-control" placeholder="Description (optional)">{{ $row['description'] ?? '' }}</textarea>
        </div>
        <div class="col-12 text-end">
            <button type="button" class="btn btn-link text-danger btn-sm remove-row">Remove</button>
        </div>
    </div>
</div>
