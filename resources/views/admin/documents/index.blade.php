{{-- resources/views/admin/offer-letters/create.blade.php --}}
@extends('layout.marketer')

@section('title', 'Send Offer Letter')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>All Documents</h4>
                <div>
                    <a href="{{ route('admin.documents.pending') }}" class="btn btn-outline-warning">
                        <i class="bi bi-clock-history"></i> View Pending ({{ \App\Models\StaffDocument::where('status', 'pending')->count() }})
                    </a>
                </div>
            </div>
            <p class="text-muted">View and manage all staff documents.</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Total Documents</h6>
                            <h3>{{ $documents->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-folder" style="font-size: 2rem;"></i>
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
                            <h6 class="text-white-50">Approved</h6>
                            <h3>{{ \App\Models\StaffDocument::where('status', 'approved')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Pending</h6>
                            <h3>{{ \App\Models\StaffDocument::where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Rejected</h6>
                            <h3>{{ \App\Models\StaffDocument::where('status', 'rejected')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
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
                                   value="{{ request('search') }}" placeholder="Title, file name, or staff name...">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="document_type" class="form-label">Document Type</label>
                            <select class="form-select" id="document_type" name="document_type">
                                <option value="">All Types</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type }}" {{ request('document_type') == $type ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($type)) }}
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
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
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
                                        <th>Document</th>
                                        <th>Staff Member</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Uploaded</th>
                                        <th>Reviewed By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $document->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $document->file_name }}</small>
                                                    @if($document->description)
                                                        <br>
                                                        <small>{{ Str::limit($document->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $document->staffProfile->user->name ?? 'Unknown' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $document->staffProfile->staff_id }}</small>
                                                    <br>
                                                    <small>{{ $document->staffProfile->department ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ str_replace('_', ' ', ucfirst($document->document_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($document->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    <br>
                                                    <small class="text-muted">{{ $document->reviewed_at ? $document->reviewed_at->format('M d, Y') : '' }}</small>
                                                @elseif($document->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                    @if($document->review_notes)
                                                        <br>
                                                        <small class="text-danger" title="{{ $document->review_notes }}">
                                                            <i class="bi bi-chat-left-text"></i> {{ Str::limit($document->review_notes, 30) }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $document->created_at->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $document->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                {{ $document->reviewer->name ?? 'Not reviewed' }}
                                                @if($document->reviewed_at)
                                                    <br>
                                                    <small class="text-muted">{{ $document->reviewed_at->format('M d, Y') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ Storage::url($document->file_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Preview">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ Storage::url($document->file_path) }}" 
                                                       download="{{ $document->file_name }}"
                                                       class="btn btn-sm btn-outline-success"
                                                       title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    @if($document->status == 'pending')
                                                        <a href="{{ route('admin.documents.review', $document->id) }}" 
                                                           class="btn btn-sm btn-outline-warning"
                                                           title="Review">
                                                            <i class="bi bi-clipboard-check"></i>
                                                        </a>
                                                    @endif
                                                    @if($document->review_notes)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-info"
                                                                onclick="showReviewNotes({{ $document->id }}, '{{ addslashes($document->review_notes) }}')"
                                                                title="View Notes">
                                                            <i class="bi bi-chat-left-text"></i>
                                                        </button>
                                                    @endif
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
                            <i class="bi bi-folder-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No documents found</h4>
                            <p class="text-muted">No documents have been uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Notes Modal -->
<div class="modal fade" id="reviewNotesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Reviewer Notes:</label>
                    <div class="border rounded p-3 bg-light" id="modalReviewNotes">
                        Loading...
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Reviewer:</label>
                        <p class="mb-0" id="modalReviewer">Loading...</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Review Date:</label>
                        <p class="mb-0" id="modalReviewDate">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showReviewNotes(documentId, notes) {
        document.getElementById('modalReviewNotes').textContent = notes;
        // You could fetch additional details via AJAX here
        new bootstrap.Modal(document.getElementById('reviewNotesModal')).show();
    }
</script>
@endpush