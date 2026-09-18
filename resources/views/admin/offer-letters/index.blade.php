{{-- resources/views/admin/offer-letters/create.blade.php --}}
@extends('layout.marketer')

@section('title', 'Offer Letter')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Sent Offer Letters</h4>
                <a href="{{ route('admin.offer-letters.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Send New Offer Letter
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by title or staff name...">
                        </div>
                        <div class="col-md-3">
                            <label for="staff" class="form-label">Staff</label>
                            <select class="form-select" id="staff" name="staff_id">
                                <option value="">All Staff</option>
                                @foreach($documents->pluck('staff')->unique()->filter() as $staff)
                                    <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->user->name ?? 'Unknown' }} ({{ $staff->staff_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.offer-letters.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Total Sent</h6>
                            <h3>{{ $documents->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-envelope-paper" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">This Month</h6>
                            <h3>{{ $documents->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Staff Member</th>
                                        <th>Effective Date</th>
                                        <th>Sent By</th>
                                        <th>Sent Date</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>
                                                <strong>{{ $document->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $document->file_name }}</small>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $document->staff->user->name ?? 'Unknown' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $document->staff->staff_id }}</small>
                                                    <br>
                                                    <small>{{ $document->staff->department ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $document->effective_date ? \Carbon\Carbon::parse($document->effective_date)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $document->reviewer->name ?? 'System' }}
                                            </td>
                                            <td>
                                                {{ $document->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                                                </span>
                                                <br>
                                                <small>{{ round($document->file_size / 1024) }} KB</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.offer-letters.preview', $document->id) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Preview">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.offer-letters.download', $document->id) }}" 
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmDelete({{ $document->id }})"
                                                            title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $documents->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-envelope-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No offer letters found</h4>
                            <p class="text-muted">No offer letters have been sent yet.</p>
                            <a href="{{ route('admin.offer-letters.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Send First Offer Letter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this offer letter? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> This will also delete the physical file.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(documentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/offer-letters/${documentId}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush