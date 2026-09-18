
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'Query Details')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <a href="{{ route('admin.hr-queries.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Queries
        </a>
        <h1>Query #{{ $query->id }}</h1>
    </div>
    <div class="col-md-4 text-end">
        @if($query->status != 'closed')
            <form action="{{ route('admin.hr-queries.close', $query->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Close this query?')">
                    <i class="bi bi-x-circle"></i> Close Query
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Query Details Card -->
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge badge-status-{{ $query->status }}">{{ ucfirst($query->status) }}</span>
                    <span class="badge bg-{{ $query->initiated_by == 'hr' ? 'info' : 'secondary' }}">
                        {{ strtoupper($query->initiated_by) }}
                    </span>
                </div>
                <small class="text-muted">{{ $query->created_at->format('F d, Y h:i A') }}</small>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $query->subject }}</h5>
                <div class="mb-3">
                    <strong>From:</strong> {{ $query->staff->full_name ?? 'N/A' }}<br>
                    @if($query->assigned_hr_id)
                        <strong>Assigned HR:</strong> {{ $query->hr->name ?? 'N/A' }}
                    @endif
                </div>
                <p class="card-text">{!! nl2br(e($query->message)) !!}</p>
                @if($query->deadline)
                    <div class="alert alert-warning">
                        <i class="bi bi-clock"></i> 
                        <strong>Deadline:</strong> 
                        {{ \Carbon\Carbon::parse($query->deadline)->format('F d, Y') }}
                        @if($query->deadline < now() && $query->status != 'closed')
                            <span class="badge bg-danger ms-2">Overdue</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Responses Section -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Responses</h5>
            </div>
            <div class="card-body">
                <!-- Existing Responses -->
                @forelse($query->responses as $response)
                <div class="mb-4 p-3 border rounded {{ $response->user->is_admin ? 'border-primary' : 'border-secondary' }}">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong>{{ $response->user->name }}</strong>
                            @if($response->user->is_admin)
                                <span class="badge bg-primary ms-2">HR</span>
                            @else
                                <span class="badge bg-secondary ms-2">Staff</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $response->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <p class="mb-0">{!! nl2br(e($response->response)) !!}</p>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-left display-4"></i>
                    <p class="mt-2">No responses yet</p>
                </div>
                @endforelse

                <!-- Response Form -->
                @if($query->status != 'closed')
                <div class="mt-4">
                    <form action="{{ route('admin.hr-queries.respond', $query->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="response" class="form-label">Add Response</label>
                            <textarea class="form-control" id="response" name="response" rows="4" required placeholder="Type your response here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Response
                        </button>
                    </form>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> This query is closed. No further responses can be added.
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Query Info Sidebar -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Query Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Query ID:</th>
                        <td>#{{ $query->id }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge badge-status-{{ $query->status }}">
                                {{ ucfirst($query->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Staff:</th>
                        <td>{{ $query->staff->full_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Employee ID:</th>
                        <td>{{ $query->staff->employee_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Department:</th>
                        <td>{{ $query->staff->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Initiated By:</th>
                        <td>{{ strtoupper($query->initiated_by) }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $query->created_at->format('F d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $query->updated_at->format('F d, Y h:i A') }}</td>
                    </tr>
                    @if($query->deadline)
                    <tr>
                        <th>Deadline:</th>
                        <td>
                            {{ \Carbon\Carbon::parse($query->deadline)->format('F d, Y') }}
                            @if($query->deadline < now() && $query->status != 'closed')
                                <span class="badge bg-danger ms-1">Overdue</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection