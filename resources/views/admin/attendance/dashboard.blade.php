@extends('layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')


@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Attendance Management</h1>
            <p class="lead">Monitor and manage staff attendance</p>
        </div>
    </div>

    <!-- Date Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <input type="date" class="form-control" name="start_date" 
                                   value="{{ request('start_date', date('Y-m-01')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <input type="date" class="form-control" name="end_date" 
                                   value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Staff</h6>
                    <h2>{{ $stats['total_staff'] }}</h2>
                    <small class="opacity-75">Active employees</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Present Today</h6>
                    <h2>{{ $stats['present_today'] }}</h2>
                    <small class="opacity-75">{{ round(($stats['present_today']/$stats['total_staff'])*100) }}% attendance</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Late Today</h6>
                    <h2>{{ $stats['late_today'] }}</h2>
                    <small class="opacity-75">Late arrivals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Absent Today</h6>
                    <h2>{{ $stats['absent_today'] }}</h2>
                    <small class="opacity-75">No clock-in recorded</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Today's Attendance</h5>
                    <span class="badge bg-primary">{{ date('l, F j, Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todaysAttendance as $attendance)
                                <tr>
                                    <td>
                                        <strong>{{ $attendance->staffProfile->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $attendance->staffProfile->staff_id }}</small>
                                    </td>
                                    <td>{{ $attendance->staffProfile->department }}</td>
                                    <td>
                                        @if($attendance->clock_in)
                                            <span class="{{ $attendance->clock_in->format('H:i') > '09:00' ? 'text-warning' : 'text-success' }}">
                                                {{ $attendance->clock_in->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-danger">--:--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->clock_out)
                                            <span class="text-success">
                                                {{ $attendance->clock_out->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-info">Still working</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->hours_worked ?: '0.00' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : ($attendance->status == 'absent' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->check_in_location)
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $attendance->check_in_location }}">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editAttendanceModal"
                                                    data-id="{{ $attendance->id }}"
                                                    data-staff="{{ $attendance->staffProfile->user->name }}"
                                                    data-date="{{ $attendance->date->format('Y-m-d') }}"
                                                    data-clock-in="{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}"
                                                    data-clock-out="{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}"
                                                    data-status="{{ $attendance->status }}"
                                                    data-notes="{{ $attendance->notes }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.attendance.staff', $attendance->staff_profile_id) }}" 
                                               class="btn btn-info">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Monthly Attendance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Department Attendance</h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Bulk Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-export fa-3x text-primary mb-3"></i>
                                    <h5>Export Attendance</h5>
                                    <p class="text-muted">Export attendance data for selected period</p>
                                    <a href="{{ route('admin.attendance.export') }}" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Export CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-none">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-plus fa-3x text-success mb-3"></i>
                                    <h5>Mark Holiday</h5>
                                    <p class="text-muted">Add holidays to attendance calendar</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                                        <i class="fas fa-plus"></i> Add Holiday
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-none">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-clock fa-3x text-info mb-3"></i>
                                    <h5>Manual Entry</h5>
                                    <p class="text-muted">Add or edit attendance records manually</p>
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#manualEntryModal">
                                        <i class="fas fa-user-edit"></i> Manual Entry
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attendance.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="attendance_id" id="edit_attendance_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Staff</label>
                        <input type="text" class="form-control" id="edit_staff_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_clock_in" class="form-label">Clock In Time</label>
                                <input type="time" class="form-control" id="edit_clock_in" name="clock_in">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_clock_out" class="form-label">Clock Out Time</label>
                                <input type="time" class="form-control" id="edit_clock_out" name="clock_out">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">On Leave</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Edit Attendance Modal
        const editModal = document.getElementById('editAttendanceModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                document.getElementById('edit_attendance_id').value = button.getAttribute('data-id');
                document.getElementById('edit_staff_name').value = button.getAttribute('data-staff');
                document.getElementById('edit_date').value = button.getAttribute('data-date');
                document.getElementById('edit_clock_in').value = button.getAttribute('data-clock-in');
                document.getElementById('edit_clock_out').value = button.getAttribute('data-clock-out');
                document.getElementById('edit_status').value = button.getAttribute('data-status');
                document.getElementById('edit_notes').value = button.getAttribute('data-notes');
            });
        }

        // Charts
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'line',
            data: @json($chartData['attendance']),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Attendance Trend'
                    }
                }
            }
        });

        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        new Chart(departmentCtx, {
            type: 'pie',
            data: @json($chartData['department']),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Attendance by Department'
                    }
                }
            }
        });
    });
</script>
@endpush