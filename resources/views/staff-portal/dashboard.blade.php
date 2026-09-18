@extends('layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')


@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Staff Portal Dashboard</h1>
            <p class="lead">Welcome, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Staff ID</h5>
                    <p class="card-text h4">{{ $staffProfile->staff_id ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Department</h5>
                    <p class="card-text h4">{{ $staffProfile->department ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Designation</h5>
                    <p class="card-text h4">{{ $staffProfile->designation ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Employment Date</h5>
                    <p class="card-text h4">{{ $staffProfile->date_of_employment->format('M Y') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('staff.portal.profile') }}" class="list-group-item list-group-item-action">
                            View/Edit Profile
                        </a>
                        <a href="{{ route('staff.portal.documents') }}" class="list-group-item list-group-item-action">
                            View Documents
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            Submit Leave Request
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            View Payslips
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Announcements</h5>
                </div>
                <div class="card-body">
                    <p>No announcements at the moment.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- staff-portal/dashboard.blade.php - Add this section -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Documents</h5>
                <a href="{{ route('staff.portal.documents') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($staffProfile->documents()->latest()->take(5)->get() as $doc)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <h6 class="mb-0">{{ $doc->title }}</h6>
                        <small class="text-muted">
                            {{ $doc->created_at->format('M d') }} • 
                            <span class="badge bg-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </small>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('staff.documents.preview', $doc->id) }}" 
                           class="btn btn-sm btn-outline-info" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">No documents uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expiring Certificates</h5>
            </div>
            <div class="card-body">
                @php
                    $expiring = $staffProfile->documents()
                        ->where('document_type', 'certificate')
                        ->where('status', 'approved')
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '>', now())
                        ->where('expiry_date', '<=', now()->addDays(30))
                        ->orderBy('expiry_date')
                        ->get();
                @endphp
                
                @forelse($expiring as $cert)
                <div class="alert alert-warning mb-2">
                    <h6 class="alert-heading">{{ $cert->title }}</h6>
                    <p class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Expires in {{ $cert->expiry_date->diffForHumans() }}
                        ({{ $cert->expiry_date->format('M d, Y') }})
                    </p>
                </div>
                @empty
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    No certificates expiring soon.
                </div>
                @endforelse
                
                <a href="{{ route('staff.documents.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-upload"></i> Upload New Certificate
                </a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection