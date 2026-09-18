{{-- resources/views/staff/dashboard.blade.php --}}
@extends('layout.staff')

@section('title', 'Dashboard - ' . config('app.name'))

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-3">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="lead mb-0">
                                {{ now()->format('l, F j, Y') }} • 
                                <span id="currentTime">{{ now()->format('h:i A') }}</span>
                            </p>
                            <small class="opacity-75">
                                <i class="fas fa-building"></i> {{ Auth::user()->staffProfile->department ?? 'Department' }} • 
                                <i class="fas fa-user-tie"></i> {{ Auth::user()->staffProfile->designation ?? 'Position' }}
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="dashboard-stats">
                                <div class="stat-item">
                                    <h2 class="mb-0">{{ $todayAttendance ? ($todayAttendance->clock_out ? 'Completed' : 'Working') : 'Not Clocked In' }}</h2>
                                    <small>Today's Status</small>
                                    @if($todayAttendance && $todayAttendance->clock_in)
                                        <p class="mb-0 small mt-1">
                                            <i class="fas fa-clock"></i> 
                                            {{ $todayAttendance->clock_in->format('h:i A') }}
                                            @if($todayAttendance->clock_out)
                                                - {{ $todayAttendance->clock_out->format('h:i A') }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4 ">
        <div class="col-md-3">
            <div class="card card-stat bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Days Present</h6>
                            <h2 class="mb-0">{{ $stats['present_days'] }}</h2>
                            <small class="opacity-75">This Month</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-white" style="width: {{ min(($stats['present_days']/20)*100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Leave Balance</h6>
                            <h2 class="mb-0">{{ $stats['leave_balance'] }}</h2>
                            <small class="opacity-75">Days Available</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-white" style="width: {{ min(($stats['leave_balance']/30)*100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Tasks</h6>
                            <h2 class="mb-0">{{ $stats['pending_tasks'] }}</h2>
                            <small class="opacity-75">Require Attention</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-white" style="width: {{ min(($stats['pending_tasks']/10)*100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Next Payday</h6>
                            <h2 class="mb-0">{{ $stats['next_payday'] }}</h2>
                            <small class="opacity-75">{{ $stats['next_payday_date'] }}</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        @php
                            $daysUntilPayday = \Carbon\Carbon::parse($stats['next_payday_date'])->diffInDays(now());
                        @endphp
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-white" style="width: {{ min((30-$daysUntilPayday)/30*100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-12">
            <!-- Attendance Widget -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Today's Attendance</h5>
                    <div class="btn-group">
                        <a href="{{ route('staff.attendance.dashboard') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-calendar-alt"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($todayAttendance)
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="attendance-status {{ $todayAttendance->clock_out ? 'completed' : 'active' }}">
                                    <div class="status-indicator"></div>
                                    <div class="status-details">
                                        <h4 class="mb-1">
                                            @if($todayAttendance->clock_out)
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Shift Completed
                                                </span>
                                            @else
                                                <span class="text-primary">
                                                    <i class="fas fa-spinner fa-spin"></i> Currently Working
                                                </span>
                                            @endif
                                        </h4>
                                        <p class="text-muted mb-0">
                                            @if($todayAttendance->clock_in)
                                                <i class="fas fa-sign-in-alt"></i> {{ $todayAttendance->clock_in->format('h:i A') }}
                                            @endif
                                            @if($todayAttendance->clock_out)
                                                <i class="fas fa-sign-out-alt ms-2"></i> {{ $todayAttendance->clock_out->format('h:i A') }}
                                            @endif
                                        </p>
                                        @if($todayAttendance->hours_worked)
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-hourglass-half"></i> {{ $todayAttendance->hours_worked }} hours worked
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="attendance-metrics">
                                    <div class="metric text-center">
                                        <h3 class="mb-0">{{ $todayAttendance->hours_worked ? number_format($todayAttendance->hours_worked, 1) : '0.0' }}</h3>
                                        <small>Hours Worked</small>
                                    </div>
                                    <div class="metric text-center">
                                        <h3 class="mb-0">
                                            <span class="badge bg-{{ $todayAttendance->status == 'present' ? 'success' : ($todayAttendance->status == 'late' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($todayAttendance->status) }}
                                            </span>
                                        </h3>
                                        <small>Status</small>
                                    </div>
                                    @if(!$todayAttendance->clock_out)
                                        <div class="metric text-center">
                                            <form action="{{ route('staff.attendance.clock') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="clock_out">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-sign-out-alt"></i> Clock Out
                                                </button>
                                            </form>
                                            <small>Clock Out</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                            <h4>Not Clocked In Today</h4>
                            <p class="text-muted">You haven't clocked in for today yet.</p>
                            <form action="{{ route('staff.attendance.clock') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="clock_in">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Clock In Now
                                </button>
                            </form>
                            <p class="text-muted mt-2 small">
                                <i class="fas fa-info-circle"></i> Office hours: 9:00 AM - 5:00 PM
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Leave -->
            <div class="card mb-4 d-none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Upcoming Leave</h5>
                    <a href="{{ route('staff.leave.dashboard') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    @if($upcomingLeave->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingLeave as $leave)
                                    <tr>
                                        <td>
                                            <i class="fas fa-{{ $leave->leaveType->code == 'AL' ? 'sun' : ($leave->leaveType->code == 'SL' ? 'heartbeat' : 'baby') }} me-2"></i>
                                            {{ $leave->leaveType->name }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $leave->start_date->format('M d') }}</small> - 
                                            <small class="text-muted">{{ $leave->end_date->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $leave->total_days > 5 ? 'warning' : 'info' }}">
                                                {{ $leave->total_days }} day{{ $leave->total_days > 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                            @if($leave->status == 'pending')
                                                <br><small class="text-muted">Awaiting approval</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.leave.view', $leave->id) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($leave->status == 'pending')
                                                <a href="{{ route('staff.leave.cancel', $leave->id) }}" 
                                                   class="btn btn-sm btn-outline-danger ms-1"
                                                   onclick="return confirm('Are you sure you want to cancel this leave application?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 ">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Upcoming Leave</h5>
                            <p class="text-muted">You don't have any upcoming leave scheduled.</p>
                            <a href="{{ route('staff.leave.apply') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Apply for Leave
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Recent Documents</h5>
                    <a href="{{ route('staff.documents.my-documents') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-folder-open"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentDocuments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentDocuments as $document)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $icon = match($document->document_type) {
                                                'certificate' => 'certificate',
                                                'tor' => 'file-contract',
                                                'contract' => 'file-signature',
                                                'payslip' => 'file-invoice-dollar',
                                                default => 'file'
                                            };
                                            
                                            $color = match($document->document_type) {
                                                'certificate' => 'warning',
                                                'tor' => 'info',
                                                'contract' => 'primary',
                                                'payslip' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <div class="document-icon bg-{{ $color }} me-3">
                                            <i class="fas fa-{{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $document->title }}</h6>
                                            <small class="text-muted">
                                                {{ ucfirst($document->document_type) }} • 
                                                {{ $document->created_at->format('M d, Y') }}
                                            </small>
                                            @if($document->status == 'pending')
                                                <span class="badge bg-warning ms-2">Pending Review</span>
                                            @elseif($document->status == 'approved')
                                                <span class="badge bg-success ms-2">Approved</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('staff.documents.view', $document->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('staff.documents.download', $document->id) }}" 
                                       class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                            <h5>No Documents Uploaded</h5>
                            <p class="text-muted">You haven't uploaded any documents yet.</p>
                            <a href="{{ route('staff.documents.upload') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4 d-none">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$todayAttendance || !$todayAttendance->clock_out)
                            <form action="{{ route('staff.attendance.clock') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="action" value="{{ $todayAttendance ? 'clock_out' : 'clock_in' }}">
                                <button type="submit" class="btn btn-{{ $todayAttendance ? 'danger' : 'success' }} btn-lg">
                                    <i class="fas fa-{{ $todayAttendance ? 'sign-out-alt' : 'sign-in-alt' }} me-2"></i>
                                    {{ $todayAttendance ? 'Clock Out' : 'Clock In' }}
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('staff.leave.apply') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-medical me-2"></i> Apply for Leave
                        </a>
                        
                        <a href="{{ route('staff.documents.upload') }}" class="btn btn-info btn-lg">
                            <i class="fas fa-upload me-2"></i> Upload Document
                        </a>
                        {{--
                        <div class="row mt-2">
                            <div class="col-6">
                                <a href="{{ route('staff.tasks.dashboard') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-tasks"></i> Tasks
                                </a> 
                            </div>
                            <div class="col-6">
                                <a href="{{ route('staff.support.tickets') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-ticket-alt"></i> Support
                                </a>
                            </div>
                        </div>
                        --}}
                        <div class="row mt-2">
                            <div class="col-6">
                                <a href="{{ route('staff.payroll.dashboard') }}" class="btn btn-success w-100">
                                    <i class="fas fa-money-bill-wave"></i> Payroll
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('profile.edit') }}" class="btn btn-dark w-100">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <!-- Upcoming Holidays -->
            <div class="card mb-4 d-none">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Upcoming Holidays</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingHolidays as $holiday)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $holiday->title }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $holiday->date->format('D, M j') }}
                                    <span class="ms-2">
                                        <i class="fas fa-clock me-1"></i>{{ $holiday->date->diffForHumans() }}
                                    </span>
                                </small>
                                @if($holiday->description)
                                    <p class="mb-0 mt-1 small text-muted">{{ Str::limit($holiday->description, 40) }}</p>
                                @endif
                            </div>
                            <span class="badge bg-{{ $holiday->type == 'public' ? 'success' : ($holiday->type == 'company' ? 'info' : 'secondary') }}">
                                {{ ucfirst($holiday->type) }}
                            </span>
                        </div>
                        @empty
                        <div class="list-group-item text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p class="mb-0">No upcoming holidays</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="card mb-4 d-none">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Recent Announcements</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($announcements as $announcement)
                        <div class="list-group-item">
                            <div class="d-flex align-items-start">
                                <div class="announcement-icon bg-{{ $announcement->priority == 'high' ? 'danger' : ($announcement->priority == 'medium' ? 'warning' : 'info') }} me-3">
                                    <i class="fas fa-{{ $announcement->priority == 'high' ? 'exclamation-triangle' : 'bullhorn' }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $announcement->title }}</h6>
                                    <p class="mb-1 small">{{ Str::limit($announcement->content, 60) }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                                        @if($announcement->department)
                                            <span class="ms-2">
                                                <i class="fas fa-building me-1"></i>{{ $announcement->department }}
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center py-4 text-muted">
                            <i class="fas fa-bullhorn fa-2x mb-2"></i>
                            <p class="mb-0">No announcements</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Leave Balance -->
            <div class="card d-none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Leave Balance</h5>
                    <a href="{{ route('staff.leave.dashboard') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </div>
                <div class="card-body">
                    <canvas id="leaveBalanceChart" height="150"></canvas>
                    <div class="mt-3">
                        @foreach($leaveBalances as $balance)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="leave-type-color me-2" style="background-color: {{ $this->getChartColor($loop->index) }}"></div>
                                <span>{{ $balance['name'] }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $balance['remaining'] > 0 ? 'success' : 'danger' }}">
                                    {{ $balance['remaining'] }} / {{ $balance['total'] }}
                                </span>
                                @if($balance['remaining'] <= 3 && $balance['remaining'] > 0)
                                    <small class="text-warning d-block">Low balance</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h5>
                    <a href="{{ route('staff.attendance.history') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($recentActivity as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $activity->color ?? 'primary' }}">
                                <i class="fas fa-{{ $activity->icon ?? 'circle' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0">{{ $activity->description }}</h6>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if(isset($activity->action_url))
                                        <a href="{{ $activity->action_url }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5>No Recent Activity</h5>
                            <p class="text-muted">Your recent activity will appear here.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="system-status-item">
                                <i class="fas fa-server fa-2x text-success mb-2"></i>
                                <h6>HR System</h6>
                                <span class="badge bg-success">Online</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="system-status-item">
                                <i class="fas fa-file-invoice-dollar fa-2x text-success mb-2"></i>
                                <h6>Payroll</h6>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="system-status-item">
                                <i class="fas fa-clock fa-2x text-success mb-2"></i>
                                <h6>Attendance</h6>
                                <span class="badge bg-success">Live</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="system-status-item">
                                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                <h6>Security</h6>
                                <span class="badge bg-success">Protected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-container {
        padding: 0 5px;
    }
    
    .bg-gradient-primary {
        background: #2b4b04  !important;
        border: none;
    }
    
    .card-stat {
        border: none;
        color: white;
        border-radius: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    .card-stat.bg-primary { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
    .card-stat.bg-success { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
    .card-stat.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
    .card-stat.bg-warning { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
    }
    
    .attendance-status {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        background: #f8f9fa;
    }
    
    .status-indicator {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-right: 15px;
    }
    
    .attendance-status.active .status-indicator {
        background-color: #28a745;
        animation: pulse 2s infinite;
    }
    
    .attendance-status.completed .status-indicator {
        background-color: #6c757d;
    }
    
    .attendance-metrics {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    
    .metric {
        text-align: center;
    }
    
    .metric h3 {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .metric small {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }
    
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
    }
    
    .timeline-content {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #3498db;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .document-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .announcement-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    
    .leave-type-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }
    
    .system-status-item {
        padding: 15px;
        border-radius: 8px;
        background: #f8f9fa;
        transition: transform 0.3s;
    }
    
    .system-status-item:hover {
        transform: translateY(-3px);
        background: #e9ecef;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .list-group-item {
        border: none;
        padding: 1rem 0;
    }
    
    .list-group-item:first-child {
        padding-top: 0;
    }
    
    .list-group-item:last-child {
        padding-bottom: 0;
    }
    
    @media (max-width: 768px) {
        .attendance-metrics {
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }
        
        .timeline {
            padding-left: 30px;
        }
        
        .timeline-marker {
            left: -30px;
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        
        .btn-lg {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        
        // Update time every minute
        updateCurrentTime();
        setInterval(updateCurrentTime, 60000);
        
        // Leave Balance Chart
        const leaveBalanceCtx = document.getElementById('leaveBalanceChart');
        
        // Chart colors
        const chartColors = [
            '#3498db', '#2ecc71', '#e74c3c', '#f39c12',
            '#9b59b6', '#1abc9c', '#34495e', '#d35400'
        ];
        
        const leaveLabels = @json($leaveBalances->pluck('name'));
        const leaveData = @json($leaveBalances->pluck('remaining'));
        const leaveTotal = @json($leaveBalances->pluck('total'));
        
        const leaveBalanceChart = new Chart(leaveBalanceCtx, {
            type: 'doughnut',
            data: {
                labels: leaveLabels,
                datasets: [{
                    data: leaveData,
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = leaveTotal[context.dataIndex];
                                const used = total - value;
                                return [
                                    `${label}: ${value} days remaining`,
                                    `Used: ${used} of ${total} days`,
                                    `Available: ${Math.round((value/total)*100)}%`
                                ];
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
        
        // Add click event to stat cards
        document.querySelectorAll('.card-stat').forEach(card => {
            card.addEventListener('click', function() {
                const title = this.querySelector('.card-title').textContent.trim();
                
                if (title.includes('Days Present')) {
                    window.location.href = '{{ route("staff.attendance.history") }}';
                } else if (title.includes('Leave Balance')) {
                    window.location.href = '{{ route("staff.leave.dashboard") }}';
                } else if (title.includes('Pending Tasks')) {
                    
                } else if (title.includes('Next Payday')) {
                    window.location.href = '{{ route("staff.payroll.dashboard") }}';
                }
            });
        });
        
        // Add hover effect to timeline items
        document.querySelectorAll('.timeline-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Clock in/out button confirmation
        document.querySelectorAll('form[action*="clock"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.querySelector('input[name="action"]').value;
                const message = action === 'clock_in' 
                    ? 'Are you sure you want to clock in?' 
                    : 'Are you sure you want to clock out?';
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
        
        // Auto-refresh dashboard every 10 minutes
        setTimeout(function() {
            window.location.reload();
        }, 10 * 60 * 1000);
        
        // Add loading animation to buttons on click
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.classList.contains('disabled')) {
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    this.classList.add('disabled');
                    
                    // Reset after 3 seconds if still disabled
                    setTimeout(() => {
                        if (this.classList.contains('disabled')) {
                            this.innerHTML = originalHTML;
                            this.classList.remove('disabled');
                        }
                    }, 3000);
                }
            });
        });
    });
    
    // Function to get chart color (used in blade)
    function getChartColor(index) {
        const colors = [
            '#3498db', '#2ecc71', '#e74c3c', '#f39c12',
            '#9b59b6', '#1abc9c', '#34495e', '#d35400'
        ];
        return colors[index % colors.length];
    }
</script>
@endpush