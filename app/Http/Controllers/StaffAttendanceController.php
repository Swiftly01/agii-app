<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\StaffProfile;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
    // Display attendance dashboard
    public function dashboard()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        // Get today's attendance
        $todayAttendance = $staffProfile->attendanceRecords()
            ->whereDate('date', today())
            ->first();
        
        // Get month statistics
        $monthStats = $this->getMonthStatistics($staffProfile);
        
        // Get recent attendance (last 7 days)
        $recentAttendance = $staffProfile->attendanceRecords()
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();
        
        // Get attendance records for current month
        $selectedMonth = request('month', date('m'));
        $selectedYear = request('year', date('Y'));
        
        $attendanceRecords = $staffProfile->attendanceRecords()
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        // Prepare calendar events
        $calendarEvents = $this->getCalendarEvents($staffProfile, $selectedYear, $selectedMonth);
        
        // Get months for dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        return view('staff-portal.attendance.dashboard', compact(
            'todayAttendance',
            'monthStats',
            'recentAttendance',
            'attendanceRecords',
            'calendarEvents',
            'months',
            'selectedMonth',
            'selectedYear'
        ));
    }
    
    // Clock in/out
    public function clock(Request $request)
    {
        $request->validate([
            'action' => 'required|in:clock_in,clock_out',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        $staffProfile = Auth::user()->staffProfile;
        $today = today();
        
        // Check if today is a holiday
        $isHoliday = Holiday::whereDate('date', $today)->exists();
        
        if ($isHoliday) {
            return back()->with('error', 'Today is a holiday. Attendance not required.');
        }
        
        if ($request->action === 'clock_in') {
            return $this->clockIn($staffProfile, $request);
        } else {
            return $this->clockOut($staffProfile, $request);
        }
    }
    
    // Clock in function
    private function clockIn($staffProfile, $request)
    {
        $today = today();
        
        // Check if already clocked in today
        $existing = $staffProfile->attendanceRecords()
            ->whereDate('date', $today)
            ->first();
            
        if ($existing && $existing->clock_in) {
            return back()->with('error', 'You have already clocked in today.');
        }
        
        $now = now();
        $clockInTime = $now->format('H:i');
        
        // Determine status (late if after 9:00 AM)
        $status = 'present';
        if ($clockInTime > '09:00') {
            $status = 'late';
        }
        
        // Create attendance record
        $attendance = $staffProfile->attendanceRecords()->updateOrCreate(
            ['date' => $today],
            [
                'clock_in' => $now,
                'status' => $status,
                'check_in_location' => $this->getLocationString($request->latitude, $request->longitude),
                'notes' => $status === 'late' ? 'Late arrival' : null,
            ]
        );
        
        // Log activity
        activity()
            ->performedOn($attendance)
            ->causedBy(Auth::user())
            ->log('Clocked in at ' . $now->format('h:i A'));
        
        return back()->with('success', 'Clocked in successfully at ' . $now->format('h:i A'));
    }
    
    // Clock out function
    private function clockOut($staffProfile, $request)
    {
        $today = today();
        
        // Get today's attendance record
        $attendance = $staffProfile->attendanceRecords()
            ->whereDate('date', $today)
            ->first();
            
        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'You must clock in before clocking out.');
        }
        
        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }
        
        $now = now();
        $clockOutTime = $now;
        
        // Calculate hours worked
        $clockInTime = Carbon::parse($attendance->clock_in);
        $hoursWorked = $clockInTime->diffInMinutes($clockOutTime) / 60;
        
        // Ensure minimum 30 minutes for half day
        if ($hoursWorked < 0.5) {
            return back()->with('error', 'Minimum work time is 30 minutes.');
        }
        
        // Update status to half day if worked less than 4 hours
        if ($hoursWorked < 4 && $attendance->status !== 'late') {
            $attendance->status = 'half_day';
        }
        
        // Update attendance record
        $attendance->update([
            'clock_out' => $now,
            'hours_worked' => round($hoursWorked, 2),
            'check_out_location' => $this->getLocationString($request->latitude, $request->longitude),
        ]);
        
        // Log activity
        activity()
            ->performedOn($attendance)
            ->causedBy(Auth::user())
            ->log('Clocked out at ' . $now->format('h:i A') . ', worked ' . round($hoursWorked, 2) . ' hours');
        
        return back()->with('success', 'Clocked out successfully. Worked ' . round($hoursWorked, 2) . ' hours today.');
    }
    
    // View attendance history
    public function history()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        $selectedMonth = request('month', date('m'));
        $selectedYear = request('year', date('Y'));
        
        $attendanceRecords = $staffProfile->attendanceRecords()
            ->when(request('month'), function($query, $month) {
                return $query->whereMonth('date', $month);
            })
            ->when(request('year'), function($query, $year) {
                return $query->whereYear('date', $year);
            })
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('date', 'desc')
            ->paginate(30);
        
        // Get months for dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        // Statistics
        $stats = [
            'total_days' => $staffProfile->attendanceRecords()->count(),
            'present_days' => $staffProfile->attendanceRecords()->where('status', 'present')->count(),
            'late_days' => $staffProfile->attendanceRecords()->where('status', 'late')->count(),
            'avg_hours' => round($staffProfile->attendanceRecords()->avg('hours_worked') ?? 0, 2),
        ];
        
        return view('staff-portal.attendance.history', compact(
            'attendanceRecords',
            'months',
            'selectedMonth',
            'selectedYear',
            'stats'
        ));
    }
    
    // Get month statistics
    private function getMonthStatistics($staffProfile)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $attendance = $staffProfile->attendanceRecords()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
        
        return [
            'present' => $attendance->where('status', 'present')->count() + 
                         $attendance->where('status', 'late')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'half_day' => $attendance->where('status', 'half_day')->count(),
            'hours' => round($attendance->sum('hours_worked'), 2),
            'avg_hours' => $attendance->count() > 0 ? 
                          round($attendance->avg('hours_worked'), 2) : 0,
        ];
    }
    
    // Get calendar events for FullCalendar
    private function getCalendarEvents($staffProfile, $year, $month)
    {
        $events = [];
        $attendanceRecords = $staffProfile->attendanceRecords()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
        
        foreach ($attendanceRecords as $record) {
            $color = $this->getStatusColor($record->status);
            $title = $this->getStatusTitle($record);
            
            $events[] = [
                'title' => $title,
                'start' => $record->date->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'status' => $record->status,
                    'clock_in' => $record->clock_in ? $record->clock_in->format('h:i A') : null,
                    'clock_out' => $record->clock_out ? $record->clock_out->format('h:i A') : null,
                    'hours' => $record->hours_worked,
                ]
            ];
        }
        
        // Add holidays
        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
            
        foreach ($holidays as $holiday) {
            $events[] = [
                'title' => $holiday->title,
                'start' => $holiday->date->format('Y-m-d'),
                'color' => '#6f42c1',
                'extendedProps' => [
                    'status' => 'holiday',
                    'type' => $holiday->type,
                ]
            ];
        }
        
        return $events;
    }
    
    private function getStatusColor($status)
    {
        return match($status) {
            'present' => '#28a745',
            'absent' => '#dc3545',
            'late' => '#ffc107',
            'half_day' => '#17a2b8',
            'leave' => '#6c757d',
            'holiday' => '#6f42c1',
            default => '#6c757d',
        };
    }
    
    private function getStatusTitle($record)
    {
        if ($record->clock_in && $record->clock_out) {
            return $record->clock_in->format('h:i A') . ' - ' . 
                   $record->clock_out->format('h:i A') . ' (' . 
                   $record->hours_worked . 'h)';
        } elseif ($record->clock_in) {
            return 'Clocked in: ' . $record->clock_in->format('h:i A');
        } else {
            return ucfirst($record->status);
        }
    }
    
    private function getLocationString($latitude, $longitude)
    {
        if (!$latitude || !$longitude) {
            return 'Location not available';
        }
        
        // In a real application, you might use a geocoding service here
        return "Lat: $latitude, Long: $longitude";
    }
}