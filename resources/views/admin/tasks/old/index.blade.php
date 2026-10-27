@extends('layout.marketer')

@section('title', 'Manage Tasks - Agii')
@section('page-title', 'Tasks Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="card-title mb-0">All Tasks</h6>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="input-group me-3" style="max-width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search tasks..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Task
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form id="filterForm" method="GET" action="{{ route('admin.tasks.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="priority" class="form-select" onchange="this.form.submit()">
                            <option value="">All Priorities</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="marketer" class="form-select" onchange="this.form.submit()">
                            <option value="">All Marketers</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}"
                                    {{ request('marketer') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->first_name }} {{ $marketer->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>
                </div>
                <input type="hidden" name="search" id="searchHidden" value="{{ request('search') }}">
            </form>
        </div>

        <div class="card-body">
            <!-- Stats Summary -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1 text-primary">{{ $stats['total'] }}</h2>
                            <p class="text-muted mb-0">Total Tasks</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1 text-warning">{{ $stats['pending'] }}</h2>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1 text-info">{{ $stats['in_progress'] }}</h2>
                            <p class="text-muted mb-0">In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1 text-success">{{ $stats['completed'] }}</h2>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr class="{{ $task->is_overdue ? 'table-danger' : '' }}">
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($task->title, 50) }}</h6>
                                            <small class="text-muted">{{ Str::limit($task->description, 70) }}</small>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>Assigned by:
                                                    {{ $task->assigner->first_name ?? 'Admin' }}
                                                    <i
                                                        class="fas fa-calendar ms-3 me-1"></i>{{ $task->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($task->marketer)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $task->marketer->profile_image ? asset('storage/' . $task->marketer->profile_image) : asset('images/default-avatar.png') }}"
                                                    alt="{{ $task->marketer->first_name }}" class="rounded-circle me-2"
                                                    width="35" height="35">
                                                <div>
                                                    <div class="fw-medium">{{ $task->marketer->first_name }}
                                                        {{ $task->marketer->last_name }}</div>
                                                    <small class="text-muted">{{ $task->marketer->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Marketer not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $priorityColors = [
                                                'high' => 'danger',
                                                'medium' => 'warning',
                                                'low' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
                                            <i class="fas fa-flag me-1"></i>{{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info',
                                                'completed' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="{{ $task->is_overdue ? 'text-danger' : '' }}">
                                            {{ $task->deadline->format('M d, Y') }}
                                            <br>
                                            <small class="{{ $task->is_overdue ? 'text-danger' : 'text-muted' }}">
                                                {{ $task->deadline->format('h:i A') }}
                                                @if ($task->is_overdue)
                                                    <br><span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $progress = $task->progress ?? 0;
                                                $progressColor = match (true) {
                                                    $progress >= 80 => 'bg-success',
                                                    $progress >= 50 => 'bg-info',
                                                    $progress >= 20 => 'bg-warning',
                                                    default => 'bg-danger',
                                                };
                                            @endphp
                                            <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                                style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $progress }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">Updated:
                                            {{ $task->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#taskDetailsModal{{ $task->id }}"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success status-btn"
                                                data-task-id="{{ $task->id }}"
                                                data-current-status="{{ $task->status }}" title="Update Status">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit Task">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $task->id }})" title="Delete Task">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Form -->
                                        <form id="deleteTaskForm{{ $task->id }}"
                                            action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                                <!-- Task Details Modal -->
                                <div class="modal fade" id="taskDetailsModal{{ $task->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Task Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Task Information</h6>
                                                        <p><strong>Title:</strong> {{ $task->title }}</p>
                                                        <p><strong>Description:</strong></p>
                                                        <p>{{ $task->description }}</p>

                                                        @if ($task->notes)
                                                            <p><strong>Notes:</strong></p>
                                                            <p>{{ $task->notes }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Assignment Details</h6>
                                                        <p><strong>Assigned To:</strong>
                                                            {{ $task->marketer->first_name ?? 'N/A' }}
                                                            {{ $task->marketer->last_name ?? '' }}
                                                        </p>
                                                        <p><strong>Assigned By:</strong>
                                                            {{ $task->assigner->first_name ?? 'Admin' }}
                                                            {{ $task->assigner->last_name ?? '' }}
                                                        </p>
                                                        <p><strong>Priority:</strong>
                                                            <span
                                                                class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
                                                                {{ ucfirst($task->priority) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Status:</strong>
                                                            <span
                                                                class="badge bg-{{ $statusColors[$task->status] ?? 'secondary' }}">
                                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Created:</strong>
                                                            {{ $task->created_at->format('M d, Y h:i A') }}</p>
                                                        <p><strong>Deadline:</strong>
                                                            {{ $task->deadline->format('M d, Y h:i A') }}</p>
                                                        <p><strong>Last Updated:</strong>
                                                            {{ $task->updated_at->format('M d, Y h:i A') }}</p>
                                                    </div>
                                                </div>

                                                <!-- Task Metrics -->
                                                @if ($task->target_leads || $task->target_conversion)
                                                    <div class="mt-4">
                                                        <h6>Task Metrics</h6>
                                                        <div class="row">
                                                            @if ($task->target_leads)
                                                                <div class="col-md-6">
                                                                    <p><strong>Target Leads:</strong>
                                                                        {{ $task->target_leads }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($task->target_conversion)
                                                                <div class="col-md-6">
                                                                    <p><strong>Target Conversion:</strong>
                                                                        {{ $task->target_conversion }}%</p>
                                                                </div>
                                                            @endif
                                                            @if ($task->actual_leads)
                                                                <div class="col-md-6">
                                                                    <p><strong>Actual Leads:</strong>
                                                                        {{ $task->actual_leads }}</p>
                                                                </div>
                                                            @endif
                                                            @if ($task->actual_conversion)
                                                                <div class="col-md-6">
                                                                    <p><strong>Actual Conversion:</strong>
                                                                        {{ $task->actual_conversion }}%</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-edit me-2"></i>Edit Task
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                    </div>
                    <div>
                        {{ $tasks->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-tasks fa-3x text-muted"></i>
                    </div>
                    <h4 class="mt-3">No Tasks Found</h4>
                    <p class="text-muted">No tasks match your search criteria.</p>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-2"></i>Create First Task
                    </a>
                    <button class="btn btn-outline-secondary mt-2 ms-2" onclick="resetFilters()">
                        <i class="fas fa-redo me-2"></i>Reset Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Task Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="progress" class="form-label">Progress (%)</label>
                            <input type="range" class="form-range" id="progress" name="progress" min="0"
                                max="100" step="5" value="0" oninput="updateProgressValue(this.value)">
                            <div class="d-flex justify-content-between mt-2">
                                <span>0%</span>
                                <span id="progressValue">0%</span>
                                <span>100%</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status_notes" class="form-label">Update Notes (Optional)</label>
                            <textarea name="status_notes" id="status_notes" class="form-control" rows="3"
                                placeholder="Add any notes about the status update..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card .card-body {
            padding: 1.25rem;
        }

        .progress {
            border-radius: 10px;
        }

        .empty-state-icon {
            opacity: 0.5;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('searchButton').addEventListener('click', function() {
            document.getElementById('searchHidden').value = document.getElementById('searchInput').value;
            document.getElementById('filterForm').submit();
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchHidden').value = this.value;
                document.getElementById('filterForm').submit();
            }
        });

        // Reset filters
        function resetFilters() {
            window.location.href = "{{ route('admin.tasks.index') }}";
        }

        // Update status modal
        document.querySelectorAll('.status-btn').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.dataset.taskId;
                const currentStatus = this.dataset.currentStatus;

                // Set form action
                const form = document.getElementById('updateStatusForm');
                form.action = `/admin/tasks/${taskId}/status`;

                // Set current status
                document.getElementById('status').value = currentStatus;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
                modal.show();
            });
        });

        // Update progress value display
        function updateProgressValue(value) {
            document.getElementById('progressValue').textContent = value + '%';
        }

        // Confirm delete
        function confirmDelete(taskId) {
            if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                document.getElementById('deleteTaskForm' + taskId).submit();
            }
        }
    </script>
@endpush
