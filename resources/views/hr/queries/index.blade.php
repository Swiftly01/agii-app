
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')
@section('title', 'HR Queries Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="bi bi-chat-left-text"></i> HR Queries Management</h1>
        <p class="text-muted">View and manage all staff queries</p>
    </div>
    <div class="col-md-4 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQueryModal">
            <i class="bi bi-plus-circle"></i> Create New Query
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Staff</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Initiated By</th>
                        <th>Created</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $query)
                    <tr class="query-card">
                        <td>#{{ $query->id }}</td>
                        <td>{{ $query->staff->full_name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.hr-queries.show', $query->id) }}" class="text-decoration-none">
                                {{ Str::limit($query->subject, 50) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-status-{{ $query->status }}">
                                {{ ucfirst($query->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $query->initiated_by == 'hr' ? 'info' : 'secondary' }}">
                                {{ strtoupper($query->initiated_by) }}
                            </span>
                        </td>
                        <td>{{ $query->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($query->deadline)
                                {{ \Carbon\Carbon::parse($query->deadline)->format('M d, Y') }}
                                @if($query->deadline < now() && $query->status != 'closed')
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            @else
                                <span class="text-muted">No deadline</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.hr-queries.show', $query->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            @if($query->status != 'closed')
                                <form action="{{ route('admin.hr-queries.close', $query->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Close this query?')">
                                        <i class="bi bi-x-circle"></i> Close
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="mt-2">No queries found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $queries->links() }}
        </div>
    </div>
</div>

<!-- Create Query Modal -->
<div class="modal fade" id="createQueryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.hr-queries.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Query</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="staff_profile_id" class="form-label">Staff Member *</label>
                        <select class="form-select" id="staff_profile_id" name="staff_profile_id" required>
                            <option value="">Select Staff</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->full_name }} ({{ $staff->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline (Optional)</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Query</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-focus first input in modal when shown
    document.getElementById('createQueryModal').addEventListener('shown.bs.modal', function () {
        document.getElementById('staff_profile_id').focus();
    });
</script>
@endpush