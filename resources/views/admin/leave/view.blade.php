{{-- resources/views/admin/leave/view.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.leave.applications') }}">Leave Applications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Application Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Application Details Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Application Details</h5>
                    <span class="badge bg-{{ $leaveApplication->status == 'approved' ? 'success' : ($leaveApplication->status == 'pending' ? 'warning' : ($leaveApplication->status == 'rejected' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($leaveApplication->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Staff Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Staff Information</h6>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-muted" style="font-size: 30px;"></i>
                            </div>
                            <h6 class="mb-0">{{ $leaveApplication->staffProfile->user->name }}</h6>
                            <small class="text-muted">{{ $leaveApplication->staffProfile->staff_id }}</small>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Department:</strong></p>
                            <p>{{ $leaveApplication->staffProfile->department }}</p>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Designation:</strong></p>
                            <p>{{ $leaveApplication->staffProfile->designation }}</p>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Employment Date:</strong></p>
                            <p>{{ $leaveApplication->staffProfile->date_of_employment->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <!-- Leave Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Leave Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Leave Type</label>
                                <div class="p-2 bg-light rounded">
                                    <strong>{{ $leaveApplication->leaveType->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $leaveApplication->leaveType->annual_entitlement }} days annual entitlement
                                    </small>
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
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Start Date</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h5 class="mb-0 text-primary">{{ $leaveApplication->start_date->format('M d, Y') }}</h5>
                                    <small>{{ $leaveApplication->start_date->format('l') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">End Date</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h5 class="mb-0 text-primary">{{ $leaveApplication->end_date->format('M d, Y') }}</h5>
                                    <small>{{ $leaveApplication->end_date->format('l') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Days</label>
                                <div class="p-2 bg-light rounded text-center">
                                    <h2 class="mb-0 text-success">{{ $leaveApplication->total_days }}</h2>
                                    <small>Working Days</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Days Remaining</label>
                                <div class="p-2 bg-light rounded text-center">
                                    @php
                                        $remainingDays = $leaveApplication->leaveType->annual_entitlement - 
                                            $leaveApplication->staffProfile->leaveApplications()
                                                ->where('leave_type_id', $leaveApplication->leave_type_id)
                                                ->where('status', 'approved')
                                                ->whereYear('start_date', now()->year)
                                                ->sum('total_days');
                                    @endphp
                                    <h2 class="mb-0 {{ $remainingDays >= $leaveApplication->total_days ? 'text-success' : 'text-danger' }}">
                                        {{ $remainingDays }}
                                    </h2>
                                    <small>Balance</small>
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
                                    <a href="#" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar - Actions & Information -->
        <div class="col-md-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-cogs"></i> Actions</h6>
                </div>
                <div class="card-body">
                    @if($leaveApplication->status == 'pending')
                        <!-- Approve/Reject Forms -->
                        <form action="{{ route('admin.leave.approve', $leaveApplication->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="approval_notes" class="form-label">Approval Notes (Optional)</label>
                                <textarea class="form-control" id="approval_notes" name="approval_notes" 
                                          rows="3" placeholder="Add notes for the staff member..."></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Approve Leave
                                </button>
                            </div>
                        </form>
                        
                        <form action="{{ route('admin.leave.reject', $leaveApplication->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rejection_notes" class="form-label">Rejection Reason *</label>
                                <textarea class="form-control" id="rejection_notes" name="approval_notes" 
                                          rows="3" placeholder="Please provide reason for rejection..." required></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Reject Leave
                                </button>
                            </div>
                        </form>
                    @elseif($leaveApplication->status == 'approved')
                        <!-- Cancel Approved Leave -->
                        @if($leaveApplication->start_date > today())
                            <form action="{{ route('admin.leave.cancel', $leaveApplication->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Warning:</strong> Cancelling approved leave will remove it from attendance records.
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-warning"
                                            onclick="return confirm('Are you sure you want to cancel this approved leave?')">
                                        <i class="fas fa-ban"></i> Cancel Approved Leave
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                This leave has already started and cannot be cancelled.
                            </div>
                        @endif
                    @endif
                    
                    <!-- General Actions -->
                    <div class="mt-3">
                        <a href="{{ route('admin.leave.applications') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-arrow-left"></i> Back to Applications
                        </a>
                        <button class="btn btn-outline-info w-100" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Application
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Staff Leave Balance -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Staff Leave Balance</h6>
                </div>
                <div class="card-body">
                    @php
                        $leaveTypes = \App\Models\LeaveType::active()->get();
                    @endphp
                    <div class="list-group list-group-flush">
                        @foreach($leaveTypes as $type)
                            @php
                                $usedDays = $leaveApplication->staffProfile->leaveApplications()
                                    ->where('leave_type_id', $type->id)
                                    ->where('status', 'approved')
                                    ->whereYear('start_date', now()->year)
                                    ->sum('total_days');
                                $remaining = max(0, $type->annual_entitlement - $usedDays);
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0">{{ $type->name }}</h6>
                                    <small class="text-muted">{{ $type->annual_entitlement }} days annual</small>
                                </div>
                                <div class="text-end">
                                    <div class="{{ $remaining >= $leaveApplication->total_days && $type->id == $leaveApplication->leave_type_id ? 'text-success' : 'text-dark' }}">
                                        {{ $remaining }}
                                    </div>
                                    <small class="text-muted">days left</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
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
                            </div>
                        </div>
                        @endif
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
        .breadcrumb, .btn, .card-header .badge, .sidebar-card {
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