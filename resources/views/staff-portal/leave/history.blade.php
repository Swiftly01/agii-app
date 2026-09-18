{{-- resources/views/staff-portal/leave/history.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Leave History</h1>
            <p class="lead">View all your leave applications</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.leave.apply') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Apply for Leave
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select class="form-select" name="year" onchange="this.form.submit()">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Leave Type</label>
                            <select class="form-select" name="type" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('staff.leave.history') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Applications Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Applications</h5>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <div class="card-body">
                    @if($leaveApplications->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No leave applications found for the selected filters.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover" id="leaveTable">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Date Range</th>
                                    <th>Total Days</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th>Approved By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveApplications as $application)
                                <tr>
                                    <td>
                                        <strong>{{ $application->leaveType->name }}</strong>
                                        @if($application->reason)
                                            <br><small class="text-muted">{{ Str::limit($application->reason, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $application->start_date->format('M d, Y') }}</span>
                                            <small class="text-muted">to</small>
                                            <span>{{ $application->end_date->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $application->total_days > 5 ? 'warning' : 'info' }}">
                                            {{ $application->total_days }} days
                                        </span>
                                    </td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$application->status] }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($application->approver)
                                            {{ $application->approver->name }}
                                            <br>
                                            <small class="text-muted">{{ $application->approved_at->format('M d') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('staff.leave.view', $application->id) }}" 
                                               class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($application->status == 'pending')
                                                <a href="{{ route('staff.leave.cancel', $application->id) }}" 
                                                   class="btn btn-warning"
                                                   onclick="return confirm('Are you sure you want to cancel this leave application?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $leaveApplications->firstItem() }} to {{ $leaveApplications->lastItem() }} 
                            of {{ $leaveApplications->total() }} records
                        </div>
                        {{ $leaveApplications->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Statistics</h5>
                </div>
                <div class="card-body">
                    @php
                        $currentYear = request('year', date('Y'));
                        $totalApplications = $leaveApplications->total();
                        $approvedApplications = $leaveApplications->where('status', 'approved')->count();
                        $pendingApplications = $leaveApplications->where('status', 'pending')->count();
                        $totalDaysApproved = $leaveApplications->where('status', 'approved')->sum('total_days');
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="p-3">
                                <h2 class="text-primary">{{ $totalApplications }}</h2>
                                <small class="text-muted">Total Applications</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3">
                                <h2 class="text-success">{{ $approvedApplications }}</h2>
                                <small class="text-muted">Approved Applications</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3">
                                <h2 class="text-warning">{{ $pendingApplications }}</h2>
                                <small class="text-muted">Pending Applications</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3">
                                <h2 class="text-info">{{ $totalDaysApproved }}</h2>
                                <small class="text-muted">Total Days Approved</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leave Type Breakdown -->
                    @if($approvedApplications > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Leave Type Breakdown</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Applications</th>
                                            <th>Total Days</th>
                                            <th>Avg. Duration</th>
                                            <th>% of Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedByType = $leaveApplications->where('status', 'approved')
                                                ->groupBy(function($item) {
                                                    return $item->leaveType->name;
                                                })
                                                ->map(function($group) {
                                                    return [
                                                        'count' => $group->count(),
                                                        'total_days' => $group->sum('total_days'),
                                                        'avg_days' => round($group->avg('total_days'), 1)
                                                    ];
                                                });
                                        @endphp
                                        
                                        @foreach($groupedByType as $type => $data)
                                        <tr>
                                            <td>{{ $type }}</td>
                                            <td>{{ $data['count'] }}</td>
                                            <td>{{ $data['total_days'] }} days</td>
                                            <td>{{ $data['avg_days'] }} days</td>
                                            <td>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar" 
                                                         role="progressbar" 
                                                         style="width: {{ ($data['count'] / $approvedApplications) * 100 }}%">
                                                    </div>
                                                </div>
                                                <small>{{ round(($data['count'] / $approvedApplications) * 100, 1) }}%</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportToExcel() {
        // Get table data
        const table = document.getElementById('leaveTable');
        const rows = table.querySelectorAll('tr');
        let csv = [];
        
        // Add headers
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.textContent.trim());
        });
        csv.push(headers.join(','));
        
        // Add data rows
        table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach((cell, index) => {
                // Skip actions column (last column)
                if (index === row.children.length - 1) return;
                
                // Remove HTML and get text content
                let text = cell.textContent.trim();
                text = text.replace(/\n/g, ' ').replace(/\s+/g, ' ');
                rowData.push(`"${text}"`);
            });
            csv.push(rowData.join(','));
        });
        
        // Create and download CSV file
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `leave_history_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush

@push('styles')
<style>
    .border-bottom {
        border-color: #dee2e6 !important;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .progress-bar {
        background-color: #007bff;
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    .table-sm th, .table-sm td {
        padding: 0.75rem;
    }
</style>
@endpush