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
                                <th>Attachment</th> <!-- New column -->
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
                                                    {{ $task->assignby_name ?? 'Admin' }}
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
                                        @if($task->attachment)
                                            <a href="{{ asset($task->attachment) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               download
                                               title="Download Attachment">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{ basename($task->attachment) }}
                                            </small>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
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
                                                        
                                                        <!-- Attachment in modal -->
                                                        @if($task->attachment)
                                                            <p><strong>Attachment:</strong></p>
                                                            <div class="mt-2">
                                                                <a href="{{ asset($task->attachment) }}" 
                                                                   class="btn btn-sm btn-primary" 
                                                                   download>
                                                                    <i class="fas fa-download me-2"></i>
                                                                    Download File
                                                                </a>
                                                                <small class="d-block text-muted mt-1">
                                                                    {{ basename($task->attachment) }}
                                                                </small>
                                                            </div>
                                                        @endif
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
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} results
                    </div>
                    <div>
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                    <h5>No tasks found</h5>
                    <p class="text-muted">Get started by creating a new task</p>
                    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Task
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Add this JavaScript section at the bottom if not already present -->
    @push('scripts')
    <script>
        // Search functionality
        document.getElementById('searchButton')?.addEventListener('click', function() {
            document.getElementById('searchHidden').value = document.getElementById('searchInput').value;
            document.getElementById('filterForm').submit();
        });

        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchHidden').value = this.value;
                document.getElementById('filterForm').submit();
            }
        });

        function resetFilters() {
            document.getElementById('filterForm').reset();
            document.getElementById('searchHidden').value = '';
            window.location.href = "{{ route('admin.tasks.index') }}";
        }

        function confirmDelete(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                document.getElementById('deleteTaskForm' + taskId).submit();
            }
        }
    </script>
    @endpush
@endsection