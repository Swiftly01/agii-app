@extends('layout.marketer')

@section('title', 'My Tasks - Agii')
@section('page-title', 'My Tasks')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header with Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">My Tasks</h1>
                        <p class="text-muted">Manage and track all your assigned tasks</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Tasks</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    In Progress</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-spinner fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Overdue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['overdue'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Statistics Row -->
        @if(isset($stats['completion_rate']) || isset($stats['average_progress']) || isset($stats['productivity_score']))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Performance Metrics</h6>
                        <div class="row text-center">
                            @if(isset($stats['completion_rate']))
                            <div class="col-md-3 col-6 mb-3">
                                <div class="metric-circle mx-auto" data-percent="{{ $stats['completion_rate'] }}">
                                    <span class="metric-value">{{ $stats['completion_rate'] }}%</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted">Completion Rate</p>
                            </div>
                            @endif
                            
                            @if(isset($stats['average_progress']))
                            <div class="col-md-3 col-6 mb-3">
                                <div class="metric-circle mx-auto" data-percent="{{ $stats['average_progress'] }}">
                                    <span class="metric-value">{{ $stats['average_progress'] }}%</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted">Avg. Progress</p>
                            </div>
                            @endif
                            
                            @if(isset($stats['productivity_score']))
                            <div class="col-md-3 col-6 mb-3">
                                <div class="metric-circle mx-auto" data-percent="{{ $stats['productivity_score'] }}">
                                    <span class="metric-value">{{ $stats['productivity_score'] }}%</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted">Productivity Score</p>
                            </div>
                            @endif
                            
                            @if(isset($stats['tasks_due_this_week']))
                            <div class="col-md-3 col-6 mb-3">
                                <div class="metric-number mx-auto">
                                    <span class="metric-value">{{ $stats['tasks_due_this_week'] }}</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted">Due This Week</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters & Search</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('marketer.tasks.index') }}" id="filterForm">
                            <div class="row g-3">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Search tasks..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <!-- Priority Filter -->
                                <div class="col-md-2">
                                    <select name="priority" class="form-control">
                                        <option value="">All Priority</option>
                                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                </div>

                                <!-- Date Range -->
                                <div class="col-md-4">
                                    <div class="row g-2">
                                        <div class="col">
                                            <input type="date" 
                                                   name="date_from" 
                                                   class="form-control" 
                                                   value="{{ request('date_from', $defaultDateFrom ?? now()->subDays(30)->format('Y-m-d')) }}">
                                        </div>
                                        <div class="col-auto">
                                            <span class="mt-2 d-block">to</span>
                                        </div>
                                        <div class="col">
                                            <input type="date" 
                                                   name="date_to" 
                                                   class="form-control" 
                                                   value="{{ request('date_to', $defaultDateTo ?? now()->addDays(30)->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Filters -->
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(isset($filterCounts))
                                        <a href="{{ route('marketer.tasks.index', array_merge(request()->except('overdue', 'page'), ['overdue' => '1'])) }}" 
                                           class="btn btn-sm {{ request('overdue') == '1' ? 'btn-danger' : 'btn-outline-danger' }}">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Overdue ({{ $filterCounts['overdue'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('marketer.tasks.index', array_merge(request()->except('upcoming', 'page'), ['upcoming' => '1'])) }}" 
                                           class="btn btn-sm {{ request('upcoming') == '1' ? 'btn-warning' : 'btn-outline-warning' }}">
                                            <i class="fas fa-clock me-1"></i>Upcoming ({{ $filterCounts['upcoming'] ?? 0 }})
                                        </a>
                                        @endif
                                        <a href="{{ route('marketer.tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                                           class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                            Pending ({{ $filterCounts['pending'] ?? $stats['pending'] }})
                                        </a>
                                        <a href="{{ route('marketer.tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}" 
                                           class="btn btn-sm {{ request('status') == 'in_progress' ? 'btn-info' : 'btn-outline-info' }}">
                                            In Progress ({{ $filterCounts['in_progress'] ?? $stats['in_progress'] }})
                                        </a>
                                        <a href="{{ route('marketer.tasks.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" 
                                           class="btn btn-sm {{ request('status') == 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                                            Completed ({{ $filterCounts['completed'] ?? $stats['completed'] }})
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter me-1"></i>Apply Filters
                                            </button>
                                            <a href="{{ route('marketer.tasks.index') }}" class="btn btn-outline-secondary ms-2">
                                                <i class="fas fa-times me-1"></i>Clear All
                                            </a>
                                        </div>
                                        
                                        <!-- Sort Options -->
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 text-muted">Sort by:</span>
                                            <select name="sort_by" class="form-control form-control-sm w-auto me-2" onchange="document.getElementById('filterForm').submit()">
                                                <option value="deadline" {{ request('sort_by', 'deadline') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                                                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
                                                <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                                                <option value="progress" {{ request('sort_by') == 'progress' ? 'selected' : '' }}>Progress</option>
                                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                            </select>
                                            <select name="sort_order" class="form-control form-control-sm w-auto" onchange="document.getElementById('filterForm').submit()">
                                                <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Tasks
                            <span class="badge bg-primary ms-2">{{ $tasks->total() }}</span>
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-muted small">Show:</span>
                            <select class="form-control form-control-sm w-auto" onchange="window.location.href = this.value">
                                <option value="{{ route('marketer.tasks.index', array_merge(request()->except(['per_page', 'page']), ['per_page' => 10])) }}" 
                                        {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                                <option value="{{ route('marketer.tasks.index', array_merge(request()->except(['per_page', 'page']), ['per_page' => 15])) }}" 
                                        {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="{{ route('marketer.tasks.index', array_merge(request()->except(['per_page', 'page']), ['per_page' => 25])) }}" 
                                        {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="{{ route('marketer.tasks.index', array_merge(request()->except(['per_page', 'page']), ['per_page' => 50])) }}" 
                                        {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        @if($tasks->count() > 0)
                            <!-- Desktop View -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="20%">Task</th>
                                            <th width="12%">Assigned By</th>
                                            <th width="8%">Priority</th>
                                            <th width="8%">Status</th>
                                            <th width="12%">Progress</th>
                                            <th width="10%">Deadline</th>
                                            <th width="10%">Attachment</th>
                                            <th width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tasks as $task)
                                            <tr class="{{ $task->is_overdue ? 'table-danger' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        <div class="me-3">
                                                            <div class="task-indicator 
                                                                @if($task->status == 'completed') bg-success
                                                                @elseif($task->is_overdue) bg-danger
                                                                @elseif($task->status == 'in_progress') bg-primary
                                                                @else bg-warning @endif">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('marketer.tasks.show', $task->id) }}" 
                                                               class="text-dark fw-bold task-title">
                                                                {{ $task->title }}
                                                            </a>
                                                            <div class="text-muted small task-description mt-1">
                                                                {{ Str::limit($task->short_description ?? $task->description, 80) }}
                                                            </div>
                                                            <div class="text-muted small mt-1">
                                                                <i class="far fa-clock me-1"></i>
                                                                {{ $task->created_at->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($task->assignby_name && $task->assigner->profile_photo)
                                                            <img src="{{ asset('storage/' . $task->assigner->profile_photo) }}" 
                                                                 class="rounded-circle me-2" 
                                                                 style="width: 32px; height: 32px; object-fit: cover;"
                                                                 alt="{{ $task->assigner_name }}">
                                                        @else
                                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                                                 style="width: 32px; height: 32px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-medium">{{ $task->assignby_name }}</div>
                                                            <div class="text-muted small">{{ $task->assigner->email ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge priority-badge bg-{{ 
                                                        $task->priority == 'high' ? 'danger' : 
                                                        ($task->priority == 'medium' ? 'warning' : 'success')
                                                    }}">
                                                        <i class="fas fa-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge status-badge bg-{{ 
                                                        $task->status == 'completed' ? 'success' : 
                                                        ($task->status == 'in_progress' ? 'primary' : 
                                                        ($task->is_overdue ? 'danger' : 'warning'))
                                                    }}">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                    @if($task->is_overdue)
                                                        <div class="text-danger small mt-1">
                                                            <i class="fas fa-exclamation-circle me-1"></i>Overdue
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                            <div class="progress-bar bg-{{ 
                                                                $task->progress == 100 ? 'success' : 
                                                                ($task->progress > 50 ? 'info' : 'primary')
                                                            }}" 
                                                                 style="width: {{ $task->progress }}%"
                                                                 role="progressbar" 
                                                                 aria-valuenow="{{ $task->progress }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="fw-medium">{{ $task->progress }}%</span>
                                                    </div>
                                                    @if($task->target_leads)
                                                        <div class="text-muted small mt-1">
                                                            <i class="fas fa-users me-1"></i>Target: {{ $task->target_leads }} leads
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="{{ $task->is_overdue ? 'text-danger' : 'text-dark' }} fw-medium">
                                                        {{ $task->deadline->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ $task->deadline->format('h:i A') }}
                                                    </div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-{{ 
                                                            $task->is_overdue ? 'danger' : 
                                                            ($task->days_remaining <= 3 ? 'warning' : 'info')
                                                        }}">
                                                            <i class="fas fa-clock me-1"></i>{{ $task->days_remaining_text }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($task->attachment)
                                                        <div class="d-flex flex-column align-items-start">
                                                            <a href="{{ asset($task->attachment) }}" 
                                                               class="btn btn-sm btn-outline-info mb-1" 
                                                               download
                                                               title="Download Attachment">
                                                                <i class="fas fa-download me-1"></i>Download
                                                            </a>
                                                            <small class="text-muted" style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ basename($task->attachment) }}
                                                            </small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">No file</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('marketer.tasks.show', $task->id) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-success d-none"
                                                                onclick="quickUpdateStatus({{ $task->id }}, 'completed')"
                                                                title="Mark Complete">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-warning d-none"
                                                                onclick="quickUpdateStatus({{ $task->id }}, 'in_progress')"
                                                                title="Set In Progress">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <div class="dropdown d-none">
                                                            <button class="btn btn-outline-secondary dropdown-toggle" 
                                                                    type="button" 
                                                                    data-bs-toggle="dropdown"
                                                                    title="More Actions">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="#" 
                                                                       onclick="showQuickNotes({{ $task->id }})">
                                                                        <i class="fas fa-sticky-note me-2"></i>Add Note
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#" 
                                                                       onclick="updateProgress({{ $task->id }})">
                                                                        <i class="fas fa-percentage me-2"></i>Update Progress
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item" href="mailto:{{ $task->assigner->email ?? '#' }}?subject=Regarding Task: {{ $task->title }}">
                                                                        <i class="fas fa-envelope me-2"></i>Contact Assigner
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile View -->
                            <div class="d-md-none">
                                @foreach($tasks as $task)
                                    <div class="card task-card border-0 border-bottom rounded-0 {{ $task->is_overdue ? 'border-danger' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="task-indicator-sm me-2 
                                                            @if($task->status == 'completed') bg-success
                                                            @elseif($task->is_overdue) bg-danger
                                                            @elseif($task->status == 'in_progress') bg-primary
                                                            @else bg-warning @endif">
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('marketer.tasks.show', $task->id) }}" 
                                                               class="fw-bold text-dark">
                                                                {{ Str::limit($task->title, 40) }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                                        <span class="badge bg-{{ 
                                                            $task->priority == 'high' ? 'danger' : 
                                                            ($task->priority == 'medium' ? 'warning' : 'success')
                                                        }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        <span class="badge bg-{{ 
                                                            $task->status == 'completed' ? 'success' : 
                                                            ($task->status == 'in_progress' ? 'primary' : 'warning')
                                                        }}">
                                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                        </span>
                                                        @if($task->is_overdue)
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="text-muted small mb-3">
                                                        <i class="fas fa-user-tie me-1"></i>{{ $task->assigner_name }}
                                                        <span class="mx-2">•</span>
                                                        <i class="far fa-calendar me-1"></i>{{ $task->created_at->format('M d') }}
                                                    </div>
                                                </div>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('marketer.tasks.show', $task->id) }}">
                                                                <i class="fas fa-eye me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="quickUpdateStatus({{ $task->id }}, 'completed')">
                                                                <i class="fas fa-check me-2"></i>Mark Complete
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="quickUpdateStatus({{ $task->id }}, 'in_progress')">
                                                                <i class="fas fa-play me-2"></i>Set In Progress
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showQuickNotes({{ $task->id }})">
                                                                <i class="fas fa-sticky-note me-2"></i>Add Note
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <!-- Progress Section -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="small">Progress: {{ $task->progress }}%</span>
                                                    <span class="small {{ $task->is_overdue ? 'text-danger' : 'text-muted' }}">
                                                        {{ $task->days_remaining_text }}
                                                    </span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $task->progress == 100 ? 'success' : 'primary' }}" 
                                                         style="width: {{ $task->progress }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Attachment Section -->
                                            @if($task->attachment)
                                            <div class="mb-3">
                                                <div class="small text-muted mb-1">Attachment</div>
                                                <a href="{{ asset($task->attachment) }}" 
                                                   class="btn btn-sm btn-outline-info w-100" 
                                                   download>
                                                    <i class="fas fa-download me-2"></i>
                                                    {{ Str::limit(basename($task->attachment), 30) }}
                                                </a>
                                            </div>
                                            @endif
                                            
                                            <!-- Deadline & Description -->
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="small text-muted">Deadline</div>
                                                    <div class="fw-medium {{ $task->is_overdue ? 'text-danger' : '' }}">
                                                        {{ $task->deadline->format('M d, Y') }}
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="small text-muted">Target</div>
                                                    <div class="fw-medium">
                                                        @if($task->target_leads)
                                                            {{ $task->target_leads }} leads
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-tasks fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted">No tasks found</h4>
                                <p class="text-muted mb-4">
                                    @if(request()->hasAny(['search', 'status', 'priority', 'date_from', 'date_to', 'overdue']))
                                        Try adjusting your filters or search terms
                                    @else
                                        You don't have any tasks assigned to you yet.
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'status', 'priority', 'date_from', 'date_to', 'overdue']))
                                    <a href="{{ route('marketer.tasks.index') }}" class="btn btn-primary">
                                        <i class="fas fa-times me-2"></i>Clear Filters
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Pagination -->
                    @if($tasks->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                                </div>
                                <div>
                                    {{ $tasks->appends(request()->except('page'))->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('marketer.tasks.modals.export')
    @include('marketer.tasks.modals.quick-update')
    @include('marketer.tasks.modals.notes')
    @include('marketer.tasks.modals.progress')
@endsection

@push('styles')
    <style>
        :root {
            --priority-high: #dc3545;
            --priority-medium: #ffc107;
            --priority-low: #28a745;
            --status-pending: #ffc107;
            --status-in-progress: #0d6efd;
            --status-completed: #28a745;
            --status-overdue: #dc3545;
        }
        
        .task-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .task-indicator-sm {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .task-card {
            transition: background-color 0.2s;
        }
        
        .task-card:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .task-title:hover {
            color: #0d6efd !important;
            text-decoration: underline;
        }
        
        .task-description {
            line-height: 1.4;
        }
        
        .priority-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .border-left-primary {
            border-left: 4px solid #0d6efd !important;
        }
        
        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }
        
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        
        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }
        
        .metric-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#0d6efd 0% var(--percentage), #e9ecef var(--percentage) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .metric-circle::before {
            content: '';
            position: absolute;
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
        }
        
        .metric-value {
            position: relative;
            z-index: 1;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .metric-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 2px solid #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .metric-circle,
            .metric-number {
                width: 60px;
                height: 60px;
            }
            
            .metric-circle::before {
                width: 50px;
                height: 50px;
            }
            
            .metric-value {
                font-size: 1rem;
            }
        }
        
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            font-weight: 500;
        }
        
        .form-control, .form-select {
            border-color: #ced4da;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize metric circles
        document.addEventListener('DOMContentLoaded', function() {
            // Animate metric circles
            document.querySelectorAll('.metric-circle').forEach(circle => {
                const percent = circle.getAttribute('data-percent');
                circle.style.setProperty('--percentage', percent + '%');
            });
            
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(element => {
                new bootstrap.Tooltip(element);
            });
        });
        
        // Quick status update
        function quickUpdateStatus(taskId, status) {
            const confirmMessage = status === 'completed' 
                ? 'Mark this task as completed?' 
                : `Set this task to ${status.replace('_', ' ')}?`;
            
            if (confirm(confirmMessage)) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('status', status);
                formData.append('progress', status === 'completed' ? 100 : 50);
                formData.append('status_notes', 'Quick update via dashboard');
                
                fetch(`/marketer/tasks/${taskId}/status`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Success', 'Task status updated successfully', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast('Error', 'Failed to update task status', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'An error occurred', 'danger');
                });
            }
        }
        
        // Show quick notes modal
        function showQuickNotes(taskId) {
            document.getElementById('notesTaskId').value = taskId;
            const modal = new bootstrap.Modal(document.getElementById('quickNotesModal'));
            modal.show();
        }
        
        // Update progress
        function updateProgress(taskId) {
            fetch(`/marketer/tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('progressTaskId').value = taskId;
                    document.getElementById('currentProgress').value = data.progress;
                    document.getElementById('progressSlider').value = data.progress;
                    document.getElementById('progressValue').textContent = data.progress + '%';
                    
                    const modal = new bootstrap.Modal(document.getElementById('progressModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'Failed to load task data', 'danger');
                });
        }
        
        // Refresh page
        function refreshPage() {
            const refreshBtn = document.querySelector('button[onclick="refreshPage()"]');
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
            refreshBtn.disabled = true;
            
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
        
        // Export functionality
        function exportTasks(format) {
            const params = new URLSearchParams(window.location.search);
            params.append('format', format);
            
            window.location.href = `{{ route('marketer.tasks.index') }}?${params.toString()}`;
        }
        
        // Toast notification
        function showToast(title, message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}:</strong> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            let container = document.getElementById('toastContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toastContainer';
                container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(container);
            }
            
            container.innerHTML += toastHtml;
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
        
        // Progress slider update
        const progressSlider = document.getElementById('progressSlider');
        const progressValue = document.getElementById('progressValue');
        
        if (progressSlider && progressValue) {
            progressSlider.addEventListener('input', function() {
                progressValue.textContent = this.value + '%';
            });
        }
        
        // Auto-refresh page every 5 minutes if not on a modal
        setInterval(() => {
            if (document.visibilityState === 'visible' && 
                !document.querySelector('.modal.show')) {
                window.location.reload();
            }
        }, 300000);
    </script>
@endpush