
@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Attendance Dashboard</h1>
            <p class="lead">Track your working hours and attendance</p>
        </div>
        <div class="col-md-4 text-end">
            <div id="currentTime" class="h4 text-primary"></div>
            <div id="currentDate" class="text-muted"></div>
        </div>
    </div>

    <!-- Clock In/Out Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Clock In/Out</h5>
                </div>
                <div class="card-body">
                    @php
                        $todayAttendance = Auth::user()->staffProfile->attendanceRecords()->today()->first();
                        $isClockedIn = $todayAttendance && $todayAttendance->clock_in && !$todayAttendance->clock_out;
                    @endphp
                    
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">Today: {{ now()->format('l, F j, Y') }}</h4>
                            @if($isClockedIn)
                                <p class="text-success mb-0">
                                    <i class="fas fa-check-circle"></i> 
                                    Clocked in at {{ $todayAttendance->clock_in->format('h:i A') }}
                                </p>
                                <small class="text-muted">You are currently at work</small>
                            @elseif($todayAttendance && $todayAttendance->clock_out)
                                <p class="text-info mb-0">
                                    <i class="fas fa-door-closed"></i> 
                                    Worked {{ $todayAttendance->hours_worked }} hours today
                                </p>
                                <small class="text-muted">Clocked out at {{ $todayAttendance->clock_out->format('h:i A') }}</small>
                            @else
                                <p class="text-warning mb-0">
                                    <i class="fas fa-door-open"></i> 
                                    Not clocked in yet
                                </p>
                                <small class="text-muted">Click the button below to clock in</small>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <form action="{{ route('staff.attendance.clock') }}" method="POST" id="clockForm">
                                @csrf
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                
                                @if(!$isClockedIn && (!$todayAttendance || !$todayAttendance->clock_out))
                                    <button type="submit" class="btn btn-success btn-lg px-5" name="action" value="clock_in">
                                        <i class="fas fa-sign-in-alt"></i> CLOCK IN
                                    </button>
                                @elseif($isClockedIn)
                                    <button type="submit" class="btn btn-danger btn-lg px-5" name="action" value="clock_out">
                                        <i class="fas fa-sign-out-alt"></i> CLOCK OUT
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-lg px-5" disabled>
                                        <i class="fas fa-ban"></i> COMPLETED
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Days Present</h6>
                    <h2>{{ $monthStats['present'] ?? 0 }}</h2>
                    <small class="opacity-75">This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Hours Worked</h6>
                    <h2>{{ $monthStats['hours'] ?? 0 }}</h2>
                    <small class="opacity-75">This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Late Arrivals</h6>
                    <h2>{{ $monthStats['late'] ?? 0 }}</h2>
                    <small class="opacity-75">This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Avg. Hours/Day</h6>
                    <h2>{{ $monthStats['avg_hours'] ?? 0 }}</h2>
                    <small class="opacity-75">This Month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Attendance Calendar</h5>
                </div>
                <div class="card-body">
                    <div id="attendanceCalendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- Legend -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-key"></i> Legend</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-success me-2" style="width: 20px; height: 20px;"></div>
                        <span>Present</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-danger me-2" style="width: 20px; height: 20px;"></div>
                        <span>Absent</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-warning me-2" style="width: 20px; height: 20px;"></div>
                        <span>Late</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-info me-2" style="width: 20px; height: 20px;"></div>
                        <span>Half Day</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-secondary me-2" style="width: 20px; height: 20px;"></div>
                        <span>Leave/Holiday</span>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Recent Attendance</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentAttendance as $record)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $record->date->format('D, M j') }}</h6>
                                <small class="text-muted">
                                    @if($record->clock_in)
                                        {{ $record->clock_in->format('h:i A') }} - 
                                        {{ $record->clock_out ? $record->clock_out->format('h:i A') : 'Present' }}
                                    @else
                                        Absent
                                    @endif
                                </small>
                            </div>
                            <span class="badge bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : ($record->status == 'absent' ? 'danger' : 'secondary')) }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            No attendance records found
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Records -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Attendance Records</h5>
                    <form class="d-flex" method="GET">
                        <select class="form-select me-2" name="month" onchange="this.form.submit()">
                            @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $selectedMonth == $monthValue ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-select" name="year" onchange="this.form.submit()">
                            @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceRecords as $record)
                                <tr>
                                    <td>{{ $record->date->format('M j, Y') }}</td>
                                    <td>{{ $record->date->format('D') }}</td>
                                    <td>{{ $record->clock_in ? $record->clock_in->format('h:i A') : '--:--' }}</td>
                                    <td>{{ $record->clock_out ? $record->clock_out->format('h:i A') : '--:--' }}</td>
                                    <td>{{ $record->hours_worked ?: '0.00' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : ($record->status == 'absent' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($record->notes)
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="popover" 
                                                    data-bs-placement="left"
                                                    data-bs-content="{{ $record->notes }}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $attendanceRecords->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById('currentTime').textContent = 
                now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('currentDate').textContent = 
                now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Get location for clock in/out
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            });
        }

        // Initialize calendar
        const calendarEl = document.getElementById('attendanceCalendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($calendarEvents),
            eventContent: function(arg) {
                const title = document.createElement('div');
                title.classList.add('fc-event-title');
                title.innerText = arg.event.title;
                
                const status = document.createElement('div');
                status.classList.add('fc-event-status', 'small');
                status.innerText = arg.event.extendedProps.status;
                
                const container = document.createElement('div');
                container.appendChild(title);
                container.appendChild(status);
                
                return { domNodes: [container] };
            },
            eventClassNames: function(arg) {
                return ['attendance-event', 'attendance-' + arg.event.extendedProps.status];
            }
        });
        calendar.render();

        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    #attendanceCalendar {
        height: 400px;
    }
    
    .fc-event {
        border: none;
        padding: 2px 5px;
    }
    
    .attendance-present { background-color: #28a745; }
    .attendance-absent { background-color: #dc3545; }
    .attendance-late { background-color: #ffc107; color: #000; }
    .attendance-half_day { background-color: #17a2b8; }
    .attendance-leave { background-color: #6c757d; }
    .attendance-holiday { background-color: #6f42c1; }
    
    .fc-event-title {
        font-weight: bold;
    }
    
    .fc-event-status {
        font-size: 0.8em;
        opacity: 0.8;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.25rem;
    }
</style>
@endpush