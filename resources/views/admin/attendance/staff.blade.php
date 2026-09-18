@extends('layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $staffProfile->user->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Staff Information -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-muted" style="font-size: 40px;"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="mb-1">{{ $staffProfile->user->name }}</h4>
                                    <p class="text-muted mb-0">{{ $staffProfile->staff_id }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Department:</strong> {{ $staffProfile->department }}</p>
                                    <p class="mb-1"><strong>Designation:</strong> {{ $staffProfile->designation }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge bg-{{ $staffProfile->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($staffProfile->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Joined:</strong> {{ $staffProfile->date_of_employment->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Present Days</h6>
                    <h2>{{ $stats['present_days'] }}</h2>
                    <small class="opacity-75">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Late Days</h6>
                    <h2>{{ $stats['late_days'] }}</h2>
                    <small class="opacity-75">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Absent Days</h6>
                    <h2>{{ $stats['absent_days'] }}</h2>
                    <small class="opacity-75">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Avg. Hours/Day</h6>
                    <h2>{{ $stats['avg_hours'] }}</h2>
                    <small class="opacity-75">Average</small>
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
                        <div class="col-md-4">
                            <label class="form-label">Month</label>
                            <select class="form-select" name="month" onchange="this.form.submit()">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <select class="form-select" name="year" onchange="this.form.submit()">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.attendance.staff', $staffProfile->id) }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance Records</h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-outline-success btn-sm d-none" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                            <i class="fas fa-plus"></i> Add Record
                        </button>
                    </div>
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
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>{{ $record->date->format('M j, Y') }}</td>
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
                                                {{ $record->hours_worked }}h
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
                                    <td>
                                        <div class="btn-group btn-group-sm d-none">
                                            <button class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editRecordModal"
                                                    data-id="{{ $record->id }}"
                                                    data-date="{{ $record->date->format('Y-m-d') }}"
                                                    data-clock-in="{{ $record->clock_in ? $record->clock_in->format('H:i') : '' }}"
                                                    data-clock-out="{{ $record->clock_out ? $record->clock_out->format('H:i') : '' }}"
                                                    data-status="{{ $record->status }}"
                                                    data-notes="{{ $record->notes }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                           {{-- <form action="{{ route('admin.attendance.delete-record', $record->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Delete this attendance record?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
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
</div>
{{--
<!-- Add Record Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addRecordModalLabel">Add Attendance Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attendance.add-record', $staffProfile->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="add_date" name="date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_clock_in" class="form-label">Clock In Time</label>
                                <input type="time" class="form-control" id="add_clock_in" name="clock_in">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_clock_out" class="form-label">Clock Out Time</label>
                                <input type="time" class="form-control" id="add_clock_out" name="clock_out">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="add_status" class="form-label">Status *</label>
                        <select class="form-select" id="add_status" name="status" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">On Leave</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="add_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Record Modal -->
<div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editRecordModalLabel">Edit Attendance Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.attendance.update-record') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="record_id" id="edit_record_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_record_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_record_date" name="date" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_record_clock_in" class="form-label">Clock In Time</label>
                                <input type="time" class="form-control" id="edit_record_clock_in" name="clock_in">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_record_clock_out" class="form-label">Clock Out Time</label>
                                <input type="time" class="form-control" id="edit_record_clock_out" name="clock_out">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_record_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_record_status" name="status">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">On Leave</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_record_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_record_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

--}}
@endsection

@push('scripts')
<script>
    // Edit Record Modal
    const editModal = document.getElementById('editRecordModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_record_id').value = button.getAttribute('data-id');
            document.getElementById('edit_record_date').value = button.getAttribute('data-date');
            document.getElementById('edit_record_clock_in').value = button.getAttribute('data-clock-in');
            document.getElementById('edit_record_clock_out').value = button.getAttribute('data-clock-out');
            document.getElementById('edit_record_status').value = button.getAttribute('data-status');
            document.getElementById('edit_record_notes').value = button.getAttribute('data-notes');
        });
    }
    
    // Export to Excel
    function exportToExcel() {
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
            row.querySelectorAll('td').forEach((cell, index) => {
                // Skip actions column (last column)
                if (index === row.children.length - 1) return;
                
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
        link.setAttribute('download', `attendance_${document.querySelector('h4').textContent.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`);
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

@push('styles')
<style>
    .badge {
        font-size: 0.8em;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush