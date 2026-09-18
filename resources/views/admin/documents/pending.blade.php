{{-- resources/views/admin/offer-letters/create.blade.php --}}
@extends('layout.marketer')

@section('title', 'Send Offer Letter')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Pending Document Approvals</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.documents.all') }}" class="btn btn-outline-primary">
                View All Documents
            </a>
        </div>
    </div>

    @if($pendingDocuments->isEmpty())
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> No pending documents for review.
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Upload Date</th>
                            <th>File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingDocuments as $document)
                        <tr>
                            <td>
                                <strong>{{ $document->staffProfile->user->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $document->staffProfile->staff_id }}</small>
                            </td>
                            <td>
                                <strong>{{ $document->title }}</strong>
                                @if($document->description)
                                <br><small class="text-muted">{{ $document->description }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ \App\Models\StaffDocument::getDocumentTypes()[$document->document_type] ?? $document->document_type }}
                                </span>
                            </td>
                            <td>{{ $document->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('staff.documents.preview', $document->id) }}" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                                <a href="{{ route('staff.documents.download', $document->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.documents.review', $document->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $pendingDocuments->links() }}
        </div>
    </div>
    @endif
</div>
@endsection