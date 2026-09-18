@extends('layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Leave Management</h1>
            <p class="lead">Apply for leave and track your leave balance</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.leave.apply') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Apply for Leave
            </a>
        </div>
    </div>

    <!-- Leave Balance Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Leave Balance Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($leaveBalances as $balance)
                        <div class="col-md-3 mb-3">
                            <div class="card border-{{ $balance['color'] }} h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-{{ $balance['color'] }}">
                                        {{ $balance['name'] }}
                                    </h6>
                                    <h2 class="{{ $balance['remaining'] <= 5 ? 'text-danger' : 'text-dark' }}">
                                        {{ $balance['remaining'] }}
                                    </h2>
                                    <div class="progress mb-2" style="height: 8px;">
                                        @php
                                            $percentage = $balance['total'] > 0 ? ($balance['used'] / $balance['total']) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-{{ $balance['color'] }}" 
                                             role="progressbar" 
                                             style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $balance['used'] }} used of {{ $balance['total'] }} days
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Pending Applications</h6>
                    <h2>{{ $stats['pending'] }}</h2>
                    <small class="opacity-75">Awaiting approval</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Approved This Year</h6>
                    <h2>{{ $stats['approved'] }}</h2>
                    <small class="opacity-75">Leave days approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Upcoming Leave</h6>
                    <h2>{{ $stats['upcoming'] }}</h2>
                    <small class="opacity-75">Days scheduled</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Leave Applications</h6>
                    <h2>{{ $stats['total'] }}</h2>
                    <small class="opacity-75">All time</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Applications -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Recent Leave Applications</h5>
                    <a href="{{ route('staff.leave.history') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Date Range</th>
                                    <th>Total Days</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $application)
                                <tr>
                                    <td>
                                        <strong>{{ $application->leaveType->name }}</strong>
                                        @if($application->reason)
                                            <br><small class="text-muted">{{ Str::limit($application->reason, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $application->start_date->format('M d') }} - 
                                        {{ $application->end_date->format('M d, Y') }}
                                    </td>
                                    <td>{{ $application->total_days }} days</td>
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
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
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
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No leave applications found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Upcoming Holidays -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar-day"></i> Upcoming Holidays</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingHolidays as $holiday)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $holiday->title }}</h6>
                                <small class="text-muted">
                                    {{ $holiday->date->format('D, M j, Y') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $holiday->type == 'public' ? 'primary' : 'info' }}">
                                {{ ucfirst($holiday->type) }}
                            </span>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            No upcoming holidays
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Apply -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Leave Application</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.leave.quick-apply') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="quick_leave_type" class="form-label">Leave Type</label>
                            <select class="form-select" id="quick_leave_type" name="leave_type_id" required>
                                <option value="">Select Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quick_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="quick_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="quick_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="quick_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="quick_reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="quick_reason" name="reason" rows="2" 
                                      placeholder="Brief reason for leave..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Submit Quick Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date for start date (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('quick_start_date').min = tomorrow.toISOString().split('T')[0];
        
        // Update end date min when start date changes
        document.getElementById('quick_start_date').addEventListener('change', function() {
            document.getElementById('quick_end_date').min = this.value;
        });
    });
</script>
@endpush