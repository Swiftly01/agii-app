<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\StaffProfile;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminAttendanceController extends Controller
{
    // Display attendance management dashboard
    public function index()
    {
        $startDate = request('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));
        $department = request('department');
        
        // Get overall statistics
        $stats = $this->getStatistics($startDate, $endDate);
        
        // Get today's attendance
        $todaysAttendance = $this->getTodaysAttendance($department);
        
        // Get departments for filter
        $departments = StaffProfile::distinct('department')->pluck('department');
        
        // Get chart data
        $chartData = $this->getChartData($startDate, $endDate);
        
        return view('admin.attendance.dashboard', compact(
            'stats',
            'todaysAttendance',
            'departments',
            'chartData',
            'startDate',
            'endDate',
            'department'
        ));
    }
    
    // View staff attendance history
    public function staff($id)
    {
        $staffProfile = StaffProfile::with('user')->findOrFail($id);
        
        $selectedMonth = request('month', date('m'));
        $selectedYear = request('year', date('Y'));
        
        $attendanceRecords = $staffProfile->attendanceRecords()
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->orderBy('date', 'desc')
            ->paginate(30);
        
        // Statistics for this staff
        $stats = [
            'present_days' => $staffProfile->attendanceRecords()
                ->where('status', 'present')
                ->count(),
            'late_days' => $staffProfile->attendanceRecords()
                ->where('status', 'late')
                ->count(),
            'absent_days' => $staffProfile->attendanceRecords()
                ->where('status', 'absent')
                ->count(),
            'avg_hours' => round($staffProfile->attendanceRecords()
                ->avg('hours_worked') ?? 0, 2),
        ];
        
        return view('admin.attendance.staff', compact(
            'staffProfile',
            'attendanceRecords',
            'stats',
            'selectedMonth',
            'selectedYear'
        ));
    }
    
    // Update attendance record
    public function update(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendance_records,id',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day,leave,holiday',
            'notes' => 'nullable|string',
        ]);
        
        $attendance = AttendanceRecord::findOrFail($request->attendance_id);
        
        // Calculate hours worked if both times are provided
        $hoursWorked = 0;
        if ($request->clock_in && $request->clock_out) {
            $clockIn = Carbon::parse($request->clock_in);
            $clockOut = Carbon::parse($request->clock_out);
            $hoursWorked = $clockIn->diffInMinutes($clockOut) / 60;
        }
        
        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'hours_worked' => $hoursWorked,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        
        // Log activity
        activity()
            ->performedOn($attendance)
            ->causedBy(auth()->user())
            ->log('Attendance record updated by admin');
        
        return back()->with('success', 'Attendance record updated successfully.');
    }
    
    // Bulk update attendance
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff_profiles,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day,leave,holiday',
            'notes' => 'nullable|string',
        ]);
        
        foreach ($request->staff_ids as $staffId) {
            AttendanceRecord::updateOrCreate(
                [
                    'staff_profile_id' => $staffId,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status,
                    'notes' => $request->notes . ' (Bulk update by admin)',
                ]
            );
        }
        
        return back()->with('success', 'Bulk attendance update completed.');
    }
    
    // Mark holiday for all staff
    public function markHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
        ]);
        
        // Create holiday record
        Holiday::create([
            'title' => $request->title,
            'date' => $request->date,
            'type' => 'company',
            'description' => 'Company holiday',
            'repeats_annually' => false,
        ]);
        
        // Mark attendance as holiday for all staff
        $staffProfiles = StaffProfile::all();
        foreach ($staffProfiles as $staff) {
            AttendanceRecord::updateOrCreate(
                [
                    'staff_profile_id' => $staff->id,
                    'date' => $request->date,
                ],
                [
                    'status' => 'holiday',
                    'notes' => 'Company holiday: ' . $request->title,
                ]
            );
        }
        
        return back()->with('success', 'Holiday marked for all staff.');
    }
    
    // Export attendance data
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'format' => 'required|in:csv,excel,pdf',
        ]);
        
        $attendanceRecords = AttendanceRecord::with('staffProfile.user')
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->orderBy('date')
            ->get();
        
        if ($request->format === 'csv') {
            return $this->exportToCsv($attendanceRecords);
        }
        
        // For other formats, you would implement here
        return back()->with('error', 'Export format not yet implemented.');
    }
    
    // Generate attendance report
    public function report()
    {
        $month = request('month', date('m'));
        $year = request('year', date('Y'));
        
        $report = $this->generateAttendanceReport($month, $year);
        
        return view('admin.attendance.report', compact('report', 'month', 'year'));
    }
    
    // Get statistics
    private function getStatistics($startDate, $endDate)
    {
        $totalStaff = StaffProfile::where('status', 'active')->count();
        
        $today = today();
        $presentToday = AttendanceRecord::whereDate('date', $today)
            ->whereIn('status', ['present', 'late', 'half_day'])
            ->count();
        
        $lateToday = AttendanceRecord::whereDate('date', $today)
            ->where('status', 'late')
            ->count();
        
        $absentToday = $totalStaff - $presentToday;
        
        return [
            'total_staff' => $totalStaff,
            'present_today' => $presentToday,
            'late_today' => $lateToday,
            'absent_today' => $absentToday,
        ];
    }
    
    // Get today's attendance
    private function getTodaysAttendance($department = null)
    {
        $query = AttendanceRecord::with(['staffProfile.user'])
            ->whereDate('date', today())
            ->orderBy('clock_in');
            
        if ($department) {
            $query->whereHas('staffProfile', function($q) use ($department) {
                $q->where('department', $department);
            });
        }
        
        return $query->get();
    }
    
    // Get chart data
    private function getChartData($startDate, $endDate)
    {
        // Monthly attendance trend
        $attendanceTrend = AttendanceRecord::selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                COUNT(*) as total,
                SUM(CASE WHEN status IN ("present", "late", "half_day") THEN 1 ELSE 0 END) as present
            ')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Department attendance
        $departmentAttendance = AttendanceRecord::selectRaw('
                staff_profiles.department,
                COUNT(*) as total,
                SUM(CASE WHEN attendance_records.status IN ("present", "late", "half_day") THEN 1 ELSE 0 END) as present
            ')
            ->join('staff_profiles', 'attendance_records.staff_profile_id', '=', 'staff_profiles.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('staff_profiles.department')
            ->get();
        
        return [
            'attendance' => [
                'labels' => $attendanceTrend->pluck('month'),
                'datasets' => [
                    [
                        'label' => 'Present',
                        'data' => $attendanceTrend->pluck('present'),
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    ],
                    [
                        'label' => 'Total Staff',
                        'data' => $attendanceTrend->pluck('total'),
                        'borderColor' => '#6c757d',
                        'backgroundColor' => 'transparent',
                        'borderDash' => [5, 5],
                    ]
                ]
            ],
            'department' => [
                'labels' => $departmentAttendance->pluck('department'),
                'datasets' => [
                    [
                        'label' => 'Attendance Rate',
                        'data' => $departmentAttendance->map(function($item) {
                            return $item->total > 0 ? round(($item->present / $item->total) * 100, 1) : 0;
                        }),
                        'backgroundColor' => [
                            '#3498db', '#2ecc71', '#e74c3c', '#f39c12', 
                            '#9b59b6', '#1abc9c', '#34495e', '#d35400'
                        ],
                    ]
                ]
            ]
        ];
    }
    
    // Export to CSV
    private function exportToCsv($records)
    {
        $filename = 'attendance_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Staff ID', 'Name', 'Date', 'Day', 'Clock In', 
                'Clock Out', 'Hours Worked', 'Status', 'Location'
            ]);
            
            // Data
            foreach ($records as $record) {
                fputcsv($file, [
                    $record->staffProfile->staff_id ?? 'N/A',
                    $record->staffProfile->user->name ?? 'N/A',
                    $record->date->format('Y-m-d'),
                    $record->date->format('l'),
                    $record->clock_in ? $record->clock_in->format('H:i:s') : '',
                    $record->clock_out ? $record->clock_out->format('H:i:s') : '',
                    $record->hours_worked,
                    $record->status,
                    $record->check_in_location ?? '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // Generate attendance report
    private function generateAttendanceReport($month, $year)
    {
        $report = [];
        
        $staffProfiles = StaffProfile::with(['user', 'attendanceRecords' => function($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                  ->whereYear('date', $year);
        }])->get();
        
        foreach ($staffProfiles as $staff) {
            $attendance = $staff->attendanceRecords;
            
            $report[] = [
                'staff_id' => $staff->staff_id,
                'name' => $staff->user->name,
                'department' => $staff->department,
                'present_days' => $attendance->whereIn('status', ['present', 'late'])->count(),
                'absent_days' => $attendance->where('status', 'absent')->count(),
                'late_days' => $attendance->where('status', 'late')->count(),
                'half_days' => $attendance->where('status', 'half_day')->count(),
                'total_hours' => round($attendance->sum('hours_worked'), 2),
                'attendance_rate' => $attendance->count() > 0 ? 
                    round(($attendance->whereIn('status', ['present', 'late', 'half_day'])->count() / $attendance->count()) * 100, 1) : 0,
            ];
        }
        
        return $report;
    }
}