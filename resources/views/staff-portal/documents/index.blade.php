
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>My Documents</h1>
            <p class="lead">Manage your certificates, TORs, and other documents</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.documents.upload') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload New Document
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total</h5>
                    <p class="card-text h4">{{ $documents->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <p class="card-text h4">{{ $documents->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="card-text h4">{{ $documents->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Certificates</h5>
                    <p class="card-text h4">{{ $documents->where('document_type', 'certificate')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">All Documents</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" placeholder="Search documents..." 
                               name="search" value="{{ request('search') }}">
                        <select class="form-control me-2" name="type">
                            <option value="">All Types</option>
                            @foreach($documentTypes as $key => $type)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary">Filter</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Upload Date</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                        <tr>
                            <td>
                                <strong>{{ $document->title }}</strong>
                                @if($document->description)
                                <br><small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $documentTypes[$document->document_type] ?? $document->document_type }}
                                </span>
                            </td>
                            <td>{{ isset($document->created_at) ? $document->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ isset($document->issue_date) ? \Carbon\Carbon::parse($document->issue_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if(isset($document->expiry_date) && $document->expiry_date)
                                    @php $expiry = \Carbon\Carbon::parse($document->expiry_date); @endphp
                                    @if($expiry->isPast())
                                        <span class="badge bg-danger">{{ $expiry->format('M d, Y') }}</span>
                                    @elseif($expiry->diffInDays(now()) < 30)
                                        <span class="badge bg-warning">{{ $expiry->format('M d, Y') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $expiry->format('M d, Y') }}</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$document->status] ?? 'secondary' }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                                @if($document->review_notes)
                                <br><small class="text-muted">{{ Str::limit($document->review_notes, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                                </span>
                                <br><small>{{ isset($document->file_size) ? round($document->file_size / 1024) : 0 }} KB</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ asset($document->file_path) }}" class="btn btn-info" target="_blank" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ asset($document->file_path) }}" download class="btn btn-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if(!str_starts_with($document->id, 'app-') && $document->status == 'pending')
                                    <form action="{{ route('staff.documents.destroy', $document->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this document?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No documents found. Upload your first document!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
