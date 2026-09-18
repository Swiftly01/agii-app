
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Upload New Document</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_type" class="form-label">Document Type *</label>
                                    <select class="form-control @error('document_type') is-invalid @enderror" 
                                            id="document_type" name="document_type" required>
                                        <option value="">Select Type</option>
                                        @foreach($documentTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Document Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="e.g., AWS Certified Solutions Architect" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description of the document">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Issue Date (Optional)</label>
                                    <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                           id="issue_date" name="issue_date" value="{{ old('issue_date') }}">
                                    @error('issue_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date (Optional)</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="document_file" class="form-label">Document File *</label>
                            <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                   id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            @error('document_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Allowed: PDF, Word (doc, docx), Images (jpg, jpeg, png). Max: 10MB
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                            <ul class="mb-0">
                                <li>All documents require admin approval before they are considered valid</li>
                                <li>Certificates with expiry dates will show warnings when about to expire</li>
                                <li>You can only delete documents that are still pending review</li>
                                <li>Keep your documents updated for HR records</li>
                            </ul>
                        </div>
                        
                        <div class="text-end">
                            <a href="{{ url('/staff/documents') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Upload Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection