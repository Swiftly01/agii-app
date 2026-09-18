{{-- resources/views/staff-portal/leave/view.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('staff.leave.dashboard') }}">Leave Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Leave Application Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Leave Application Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Application Details</h5>
                    <span class="badge bg-{{ $leaveApplication->status == 'approved' ? 'success' : ($leaveApplication->status == 'pending' ? 'warning' : ($leaveApplication->status == 'rejected' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($leaveApplication->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Leave Type</label>
                                <div class="p-2 bg-light rounded">
                                    <strong>{{ $leaveApplication->leaveType->name }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Application Date</label>
                                <div class="p-2 bg-light rounded">
                                    <strong>{{ $leaveApplication->created_at->format('M d, Y h:i A') }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Reference Number</label>
                                <div class="p-2 bg-light rounded">
                                    <strong>LA{{ str_pad($leaveApplication->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leave Duration -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Leave Duration</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Start Date</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h5 class="mb-0 text-primary">{{ $leaveApplication->start_date->format('M d, Y') }}</h5>
                                    <small>{{ $leaveApplication->start_date->format('l') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">End Date</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h5 class="mb-0 text-primary">{{ $leaveApplication->end_date->format('M d, Y') }}</h5>
                                    <small>{{ $leaveApplication->end_date->format('l') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Days</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h2 class="mb-0 text-success">{{ $leaveApplication->total_days }}</h2>
                                    <small>Working Days</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reason & Details -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Reason & Details</h6>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Reason for Leave</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $leaveApplication->reason }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    @if($leaveApplication->emergency_contact || $leaveApplication->handover_to)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Additional Information</h6>
                        </div>
                        
                        @if($leaveApplication->emergency_contact)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Emergency Contact</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $leaveApplication->emergency_contact }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($leaveApplication->handover_to)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Handover To</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $leaveApplication->handover_to }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Approval Information -->
                    @if($leaveApplication->status != 'pending')
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Approval Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Approved By</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $leaveApplication->approver->name ?? 'System' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Approval Date</label>
                                <div class="p-2 bg-light rounded">
                                    {{ $leaveApplication->approved_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        @if($leaveApplication->approval_notes)
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Approval Notes</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $leaveApplication->approval_notes }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Attachment -->
                    @if($leaveApplication->attachment_path)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Supporting Document</h6>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-paperclip"></i>
                                        <strong>Attachment Available</strong>
                                        <br>
                                        <small class="text-muted">Supporting document for leave application</small>
                                    </div>
                                    <a href="{{ route('staff.leave.download', $leaveApplication->id) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('staff.leave.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                                
                                <div class="btn-group">
                                    @if($leaveApplication->status == 'pending')
                                        <a href="{{ route('staff.leave.cancel', $leaveApplication->id) }}" 
                                           class="btn btn-warning"
                                           onclick="return confirm('Are you sure you want to cancel this leave application?')">
                                            <i class="fas fa-times"></i> Cancel Application
                                        </a>
                                    @endif
                                    
                                    <button class="btn btn-info" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Application Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Application Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $leaveApplication->status == 'pending' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Application Submitted</h6>
                                <small class="text-muted">{{ $leaveApplication->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($leaveApplication->status == 'approved' || $leaveApplication->status == 'rejected')
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">{{ ucfirst($leaveApplication->status) }}</h6>
                                <small class="text-muted">{{ $leaveApplication->approved_at->format('M d, Y h:i A') }}</small>
                                <br>
                                <small>By: {{ $leaveApplication->approver->name ?? 'System' }}</small>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item pending">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Awaiting Approval</h6>
                                <small class="text-muted">Pending supervisor review</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($leaveApplication->status == 'approved')
                        <div class="timeline-item {{ $leaveApplication->start_date <= today() ? 'active' : 'pending' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Leave Period</h6>
                                <small class="text-muted">
                                    {{ $leaveApplication->start_date->format('M d') }} - 
                                    {{ $leaveApplication->end_date->format('M d, Y') }}
                                </small>
                                <br>
                                <small>
                                    @if($leaveApplication->end_date < today())
                                        <span class="text-success">Completed</span>
                                    @elseif($leaveApplication->start_date <= today() && $leaveApplication->end_date >= today())
                                        <span class="text-warning">Currently on leave</span>
                                    @else
                                        <span class="text-info">Upcoming</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($leaveApplication->status == 'pending')
                            <a href="{{ route('staff.leave.apply') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit Application
                            </a>
                        @endif
                        
                        <a href="{{ route('staff.leave.apply') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Apply for New Leave
                        </a>
                        
                        <a href="{{ route('staff.leave.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history"></i> View All Applications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-bottom {
        border-color: #dee2e6 !important;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 3px solid #ffffff;
    }
    
    .timeline-item.completed .timeline-marker {
        background-color: #28a745;
    }
    
    .timeline-item.active .timeline-marker {
        background-color: #007bff;
        animation: pulse 2s infinite;
    }
    
    .timeline-item.pending .timeline-marker {
        background-color: #ffc107;
    }
    
    .timeline-content {
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .timeline-item:last-child .timeline-content {
        border-bottom: none;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }
    
    @media print {
        .breadcrumb, .btn, .card-header .badge, .timeline, .sidebar-card {
            display: none !important;
        }
        
        .card {
            border: none !important;
        }
        
        .card-body {
            padding: 0 !important;
        }
    }
</style>
@endpush