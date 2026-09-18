@extends('layout.layout')
@section('title', 'Employment Applications - Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-briefcase me-2 text-primary"></i>Employment Applications
            </h1>
            <p class="text-muted mb-0">Manage and review all employment applications</p>
        </div>
        <div>
            
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                </ul>
            </div>
            
            <button type="button" class="btn btn-info" onclick="window.location.href='https://agii.ng/admin/dashboard'">
                    <i class="fas fa-chevron-left me-2"></i>Dashboard
                </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Applications
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $applications->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Pending Review
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $applications->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Shortlisted
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $applications->where('status', 'shortlisted')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Hired
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $applications->where('status', 'hired')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Attendance
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <a href="{{ url('admin/attendance') }}">View Attendance</a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                 Queries
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                <a class="nav-link" href="{{ route('admin.hr-queries.index') }}">
                                    <i class="bi bi-inbox"></i> All Queries
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        

    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-start border-primary border-4 shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                        Send Offer Letter
                    </div>
                    <div class="h5 mb-0 fw-bold text-gray-800">
                        <a href="{{ url('admin/offer-letters/send') }}">Offer Letter</a>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-file-signature fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

        
        
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employment.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" placeholder="Search by name, position, reference...">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="from_date" 
                               value="{{ request('from_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" name="to_date" 
                               value="{{ request('to_date') }}">
                    </div>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0">
                <i class="fas fa-table me-2"></i>Applications List
                <span class="badge black ms-2 text-white">{{ $applications->total() }}</span>
            </h6>
            
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i>Actions
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item bulk-action" href="#" data-action="shortlist">
                        <i class="fas fa-list-check me-2"></i>Mark as Shortlisted
                    </a></li>
                    <li><a class="dropdown-item bulk-action" href="#" data-action="reject">
                        <i class="fas fa-times-circle me-2"></i>Mark as Rejected
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger bulk-action" href="#" data-action="delete">
                        <i class="fas fa-trash me-2"></i>Delete Selected
                    </a></li>
                </ul>
            </div>
        </div>
        
        <div class="card-body">
            @if($applications->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-file-search fa-3x text-muted mb-3"></i>
                    <h5>No applications found</h5>
                    <p class="text-muted">No employment applications match your filters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Reference</th>
                                <th>Applicant</th>
                                <th>Position</th>
                                <th>Qualification</th>
                                <th>Experience</th>
                                <th>Status</th>
                                <th>Date Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input application-checkbox" 
                                               type="checkbox" value="{{ $application->id }}">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace">
                                        {{ $application->application_reference }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($application->passport_photo)
                                            <img src="{{ asset( $application->passport_photo) }}" 
                                                 alt="Photo" class="rounded-circle me-3" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $application->surname . ' ' . $application->first_name }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-phone fa-xs me-1"></i>{{ $application->contact_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $application->position_applying_for }}</span>
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt fa-xs me-1"></i>{{ $application->state_of_origin }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $application->educational_qualification }}</span>
                                    @if($application->professional_qualifications)
                                      <div class="text-muted small mt-1">
                                        <i class="fas fa-award fa-xs me-1"></i>
                                        {{ count($application->professional_qualifications ?? []) }} certifications
                                    </div>

                                    @endif
                                </td>
                                <td>
                                    @php
                                    $employmentHistory = is_string($application->employment_history)
                                        ? json_decode($application->employment_history, true)
                                        : ($application->employment_history ?? []);

                                        $years = 0;
                                        foreach($employmentHistory as $job) {
                                            if(isset($job['from_date']) && isset($job['to_date'])) {
                                                $from = \Carbon\Carbon::parse($job['from_date']);
                                                $to = isset($job['to_date']) ? \Carbon\Carbon::parse($job['to_date']) : now();
                                                $years += $from->diffInYears($to);
                                            }
                                        }
                                    @endphp
                                    <div class="fw-bold">{{ $years }} years</div>
                                    <div class="text-muted small">{{ count($employmentHistory) }} positions</div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'under_review' => 'info',
                                            'shortlisted' => 'primary',
                                            'rejected' => 'danger',
                                            'hired' => 'success'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'clock',
                                            'under_review' => 'search',
                                            'shortlisted' => 'list-check',
                                            'rejected' => 'times-circle',
                                            'hired' => 'check-circle'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $statusIcons[$application->status] ?? 'circle' }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $application->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.employment.show', $application) }}" 
                                           class="btn btn-outline-info" data-bs-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-warning dropdown-toggle" 
                                                data-bs-toggle="dropdown" data-bs-toggle="tooltip" title="Change Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item status-change" href="#" 
                                                   data-id="{{ $application->id }}" data-status="pending">
                                                <i class="fas fa-clock text-warning me-2"></i>Mark as Pending
                                            </a></li>
                                            <li><a class="dropdown-item status-change" href="#" 
                                                   data-id="{{ $application->id }}" data-status="under_review">
                                                <i class="fas fa-search text-info me-2"></i>Mark as Under Review
                                            </a></li>
                                            <li><a class="dropdown-item status-change" href="#" 
                                                   data-id="{{ $application->id }}" data-status="shortlisted">
                                                <i class="fas fa-list-check text-primary me-2"></i>Mark as Shortlisted
                                            </a></li>
                                            <li><a class="dropdown-item status-change" href="#" 
                                                   data-id="{{ $application->id }}" data-status="rejected">
                                                <i class="fas fa-times-circle text-danger me-2"></i>Mark as Rejected
                                            </a></li>
                                            <li><a class="dropdown-item status-change" href="#" 
                                                   data-id="{{ $application->id }}" data-status="hired">
                                                <i class="fas fa-check-circle text-success me-2"></i>Mark as Hired
                                            </a></li>
                                        </ul>
                                        <a href="{{ route('admin.employment.download', ['application' => $application, 'type' => 'resume']) }}" 
                                           class="btn btn-outline-success" data-bs-toggle="tooltip" title="Download Resume">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} 
                    of {{ $applications->total() }} applications
                </div>
                <nav aria-label="Page navigation">
                    {{ $applications->withQueryString()->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>

    <!-- Bulk Actions Card -->
    <div class="card mt-4" id="bulkActionsCard" style="display: none;">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Bulk Actions</h6>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span id="selectedCount">0</span> applications selected
                </div>
                <div class="col-md-8">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="bulkAction">
                            <option value="">Choose action...</option>
                            <option value="pending">Mark as Pending</option>
                            <option value="under_review">Mark as Under Review</option>
                            <option value="shortlisted">Mark as Shortlisted</option>
                            <option value="rejected">Mark as Rejected</option>
                            <option value="hired">Mark as Hired</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button type="button" class="btn btn-primary" id="applyBulkAction">Apply</button>
                        <button type="button" class="btn btn-secondary" id="clearSelection">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusChangeForm">
                    @csrf
                    <input type="hidden" name="application_id" id="applicationId">
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" name="status" id="statusSelect" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="under_review">Under Review</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="rejected">Rejected</option>
                            <option value="hired">Hired</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" 
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Update Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Font Awesome Free -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
/>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, .9) !important;
    }
    
    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    }
    
    .dropdown-menu {
        min-width: 200px;
    }
    
    .status-badge {
        min-width: 100px;
        text-align: center;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .black{
        background: #000;
        color:#fff ;
    }
    
    

    .text-warning {
      color: rgb(225, 169, 39) !important;
    }
    
    .text-info {

  color: rgb(6, 123, 185) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Bulk selection functionality
    const selectAll = document.getElementById('selectAll');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const bulkActionsCard = document.getElementById('bulkActionsCard');
    const selectedCount = document.getElementById('selectedCount');
    const clearSelectionBtn = document.getElementById('clearSelection');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');

    // Select all checkbox
    selectAll.addEventListener('change', function() {
        const isChecked = this.checked;
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateBulkActions();
    });

    // Individual checkbox changes
    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Update bulk actions UI
    function updateBulkActions() {
        const selectedApplications = Array.from(applicationCheckboxes).filter(cb => cb.checked);
        const count = selectedApplications.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActionsCard.style.display = 'block';
            selectAll.indeterminate = count > 0 && count < applicationCheckboxes.length;
        } else {
            bulkActionsCard.style.display = 'none';
            selectAll.indeterminate = false;
            selectAll.checked = false;
        }
    }

    // Clear selection
    clearSelectionBtn.addEventListener('click', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
        bulkActionSelect.value = '';
    });

    // Apply bulk action
    applyBulkActionBtn.addEventListener('click', function() {
        const action = bulkActionSelect.value;
        const selectedIds = Array.from(applicationCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (selectedIds.length === 0) {
            alert('Please select at least one application');
            return;
        }

        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} application(s)? This action cannot be undone.`)) {
                return;
            }
        } else {
            if (!confirm(`Are you sure you want to mark ${selectedIds.length} application(s) as ${action.replace('_', ' ')}?`)) {
                return;
            }
        }

        // Submit bulk action
        submitBulkAction(action, selectedIds);
    });

    // Status change for individual applications
    const statusChangeButtons = document.querySelectorAll('.status-change');
    const statusChangeModal = new bootstrap.Modal(document.getElementById('statusChangeModal'));
    const statusSelect = document.getElementById('statusSelect');
    const applicationIdInput = document.getElementById('applicationId');
    const confirmStatusChangeBtn = document.getElementById('confirmStatusChange');
    let currentApplicationId = null;

    statusChangeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentApplicationId = this.dataset.id;
            const currentStatus = this.dataset.status;
            
            applicationIdInput.value = currentApplicationId;
            statusSelect.value = currentStatus;
            
            statusChangeModal.show();
        });
    });

    // Confirm status change
    confirmStatusChangeBtn.addEventListener('click', function() {
        const form = document.getElementById('statusChangeForm');
        const formData = new FormData(form);

        fetch(`/admin/employment/applications/${currentApplicationId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: statusSelect.value,
                notes: document.getElementById('notes').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated status
                location.reload();
            } else {
                alert('Failed to update status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    });

    // Bulk action submission
    function submitBulkAction(action, ids) {
        fetch('{{ route("admin.employment.bulk-action") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                application_ids: ids
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and reload
                showToast('Success', data.message || 'Bulk action completed successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Error', data.message || 'Failed to perform bulk action', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while performing bulk action', 'error');
        });
    }

    // Toast notification function
    function showToast(title, message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer') || createToastContainer();
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.id = toastId;
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
        return container;
    }

    // Filter form date range defaults
    const dateInputs = document.querySelectorAll('input[type="date"]');
    if (!dateInputs[0].value && !dateInputs[1].value) {
        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);
        
        dateInputs[0].value = thirtyDaysAgo.toISOString().split('T')[0];
        dateInputs[1].value = today.toISOString().split('T')[0];
    }

    // Auto-close alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush