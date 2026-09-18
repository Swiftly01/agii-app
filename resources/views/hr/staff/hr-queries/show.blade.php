
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')
@section('title', 'Query Details')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('staff.hr-queries.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to My Queries
        </a>
        <h1>Query #{{ $query->id }}</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
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
                    <strong>To:</strong> HR Department
                    @if($query->assigned_hr_id)
                        (Assigned to: {{ $query->hr->name ?? 'N/A' }})
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
                <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Conversation</h5>
            </div>
            <div class="card-body">
                <!-- Chat-style responses -->
                @foreach($query->responses as $response)
                <div class="mb-3">
                    <div class="d-flex {{ $response->user->is_admin ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="card {{ $response->user->is_admin ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary' }}" style="max-width: 80%;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="{{ $response->user->is_admin ? 'text-primary' : 'text-secondary' }}">
                                        {{ $response->user->name }}
                                        @if($response->user->is_admin)
                                            <span class="badge bg-primary ms-1">HR</span>
                                        @else
                                            <span class="badge bg-secondary ms-1">You</span>
                                        @endif
                                    </strong>
                                    <small class="text-muted">{{ $response->created_at->format('M d, h:i A') }}</small>
                                </div>
                                <p class="mb-0">{!! nl2br(e($response->response)) !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Response Form -->
                @if($query->status != 'closed')
                <div class="mt-4">
                    <form action="{{ route('staff.hr-queries.respond', $query->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="response" class="form-label">Add Your Response</label>
                            <textarea class="form-control" id="response" name="response" rows="4" required placeholder="Type your response here..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Response
                            </button>
                        </div>
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
</div>

<!-- Query Status Card -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Query Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Status:</strong>
                    <span class="badge badge-status-{{ $query->status }} ms-2">
                        {{ ucfirst($query->status) }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Query ID:</strong> #{{ $query->id }}
                </div>
                <div class="mb-2">
                    <strong>Created:</strong> {{ $query->created_at->format('F d, Y') }}
                </div>
                <div class="mb-2">
                    <strong>Last Updated:</strong> {{ $query->updated_at->format('F d, Y') }}
                </div>
                @if($query->deadline)
                <div class="mb-2">
                    <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($query->deadline)->format('F d, Y') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection