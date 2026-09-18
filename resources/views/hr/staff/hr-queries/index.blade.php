
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'My HR Queries')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="bi bi-chat-left-text"></i> My HR Queries</h1>
        <p class="text-muted">View and manage your queries with HR</p>
    </div>
    <div class="col-md-4 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQueryModal">
            <i class="bi bi-plus-circle"></i> New Query
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
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Last Response</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $query)
                    <tr class="query-card">
                        <td>#{{ $query->id }}</td>
                        <td>
                            <a href="{{ route('staff.hr-queries.show', $query->id) }}" class="text-decoration-none">
                                {{ Str::limit($query->subject, 60) }}
                            </a>
                            @if($query->responses->count() > 0)
                                <span class="badge bg-info ms-2">{{ $query->responses->count() }} responses</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-status-{{ $query->status }}">
                                {{ ucfirst($query->status) }}
                            </span>
                        </td>
                        <td>
                            @if($query->responses->count() > 0)
                                {{ $query->responses->last()->created_at->diffForHumans() }}
                            @else
                                <span class="text-muted">No responses</span>
                            @endif
                        </td>
                        <td>{{ $query->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('staff.hr-queries.show', $query->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="mt-2">No queries yet</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQueryModal">
                                <i class="bi bi-plus-circle"></i> Create Your First Query
                            </button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.hr-queries.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New HR Query</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required maxlength="255" placeholder="Brief description of your query">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Describe your query in detail..."></textarea>
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
    document.getElementById('createQueryModal').addEventListener('shown.bs.modal', function () {
        document.getElementById('subject').focus();
    });
</script>
@endpush