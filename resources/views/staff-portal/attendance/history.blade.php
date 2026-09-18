{{-- resources/views/staff-portal/attendance/history.blade.php --}}

@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Attendance History</h1>
            <p class="lead">View your attendance records and statistics</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.attendance.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Days</h6>
                    <h2>{{ $stats['total_days'] }}</h2>
                    <small class="opacity-75">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Present Days</h6>
                    <h2>{{ $stats['present_days'] }}</h2>
                    <small class="opacity-75">{{ $stats['total_days'] > 0 ? round(($stats['present_days']/$stats['total_days'])*100, 1) : 0 }}% Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Late Days</h6>
                    <h2>{{ $stats['late_days'] }}</h2>
                    <small class="opacity-75">Late arrivals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Avg. Hours/Day</h6>
                    <h2>{{ $stats['avg_hours'] }}</h2>
                    <small class="opacity-75">Average working hours</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Month</label>
                            <select class="form-select" name="month" onchange="this.form.submit()">
                                @foreach($months as $key => $month)
                                    <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select class="form-select" name="year" onchange="this.form.submit()">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('staff.attendance.history') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance Records</h5>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <div class="card-body">
                    @if($attendanceRecords->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No attendance records found for the selected period.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours Worked</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>
                                        <strong>{{ $record->date->format('M j, Y') }}</strong>
                                    </td>
                                    <td>{{ $record->date->format('D') }}</td>
                                    <td>
                                        @if($record->clock_in)
                                            <span class="{{ $record->clock_in->format('H:i') > '09:00' ? 'text-warning' : 'text-success' }}">
                                                {{ $record->clock_in->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-danger">--:--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->clock_out)
                                            <span class="text-success">
                                                {{ $record->clock_out->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-muted">--:--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->hours_worked)
                                            <span class="badge bg-{{ $record->hours_worked >= 8 ? 'success' : 'warning' }}">
                                                {{ $record->hours_worked }} hours
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : ($record->status == 'absent' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($record->check_in_location)
                                            <small class="text-muted">{{ Str::limit($record->check_in_location, 20) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->notes)
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="popover" 
                                                    title="Notes"
                                                    data-bs-content="{{ $record->notes }}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $attendanceRecords->firstItem() }} to {{ $attendanceRecords->lastItem() }} 
                            of {{ $attendanceRecords->total() }} records
                        </div>
                        {{ $attendanceRecords->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    @if(!$attendanceRecords->isEmpty())
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $presentCount = $attendanceRecords->where('status', 'present')->count();
                            $lateCount = $attendanceRecords->where('status', 'late')->count();
                            $absentCount = $attendanceRecords->where('status', 'absent')->count();
                            $totalHours = $attendanceRecords->sum('hours_worked');
                            $workingDays = $attendanceRecords->count();
                        @endphp
                        
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Working Days:</strong></td>
                                    <td>{{ $workingDays }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Present Days:</strong></td>
                                    <td class="text-success">{{ $presentCount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Late Days:</strong></td>
                                    <td class="text-warning">{{ $lateCount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Absent Days:</strong></td>
                                    <td class="text-danger">{{ $absentCount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Attendance Rate:</strong></td>
                                    <td class="text-primary">
                                        {{ $workingDays > 0 ? round((($presentCount + $lateCount) / $workingDays) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Hours Worked:</strong></td>
                                    <td class="text-success">{{ round($totalHours, 2) }} hours</td>
                                </tr>
                                <tr>
                                    <td><strong>Average Hours/Day:</strong></td>
                                    <td>{{ $workingDays > 0 ? round($totalHours / $workingDays, 2) : 0 }} hours</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Overtime:</strong></td>
                                    <td class="text-warning">
                                        @php
                                            $overtime = $attendanceRecords->sum(function($record) {
                                                return max(0, $record->hours_worked - 8);
                                            });
                                        @endphp
                                        {{ round($overtime, 2) }} hours
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Early Departures:</strong></td>
                                    <td class="text-info">
                                        {{ $attendanceRecords->where('hours_worked', '<', 8)->count() }} days
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function exportToExcel() {
        // Get table data
        const table = document.getElementById('attendanceTable');
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
            row.querySelectorAll('td').forEach(cell => {
                // Remove badge HTML and get text content
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
        link.setAttribute('download', `attendance_history_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Initialize popovers
    document.addEventListener('DOMContentLoaded', function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>
@endpush