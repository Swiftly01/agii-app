<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\LeaveApplication;
use App\Models\StaffDocument;
use App\Models\Holiday;
// use App\Models\Announcement;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    // Display staff dashboard
    public function index()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        // Get today's attendance
        $todayAttendance = $staffProfile->attendanceRecords()
            ->whereDate('date', today())
            ->first();
        
        // Get statistics
        $stats = $this->getDashboardStats($staffProfile);
        
        // Get upcoming leave (next 30 days)
        $upcomingLeave = $staffProfile->leaveApplications()
            ->with('leaveType')
            ->where('start_date', '>=', today())
            ->where('start_date', '<=', today()->addDays(30))
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('start_date')
            ->take(5)
            ->get();
        
        // Get recent documents
        $recentDocuments = $staffProfile->documents()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get upcoming holidays (next 30 days)
        $upcomingHolidays = Holiday::whereDate('date', '>=', today())
            ->whereDate('date', '<=', today()->addDays(30))
            ->orderBy('date')
            ->take(5)
            ->get();
        
        // Get recent announcements
        // $announcements = Announcement::where(function($query) use ($staffProfile) {
        //         $query->whereNull('department')
        //               ->orWhere('department', $staffProfile->department);
        //     })
        //     ->whereDate('created_at', '>=', today()->subDays(7))
        //     ->orderBy('created_at', 'desc')
        //     ->take(5)
        //     ->get();
        $announcements = [];
        // Get leave balances
        $leaveBalances = $this->getLeaveBalances($staffProfile);
        
        // Get recent activity (you'll need to implement this based on your activity logging)
        $recentActivity = $this->getRecentActivity($staffProfile);
        
        return view('staff.dashboard', compact(
            'todayAttendance',
            'stats',
            'upcomingLeave',
            'recentDocuments',
            'upcomingHolidays',
            'announcements',
            'leaveBalances',
            'recentActivity'
        ))->with('getChartColor', [$this, 'getChartColor']);;
    }
    
    // Add this method to StaffDashboardController.php
public function getChartColor($index)
{
    $colors = [
        '#3498db', '#2ecc71', '#e74c3c', '#f39c12',
        '#9b59b6', '#1abc9c', '#34495e', '#d35400'
    ];
    
    return $colors[$index % count($colors)];
}
    // Get dashboard statistics
    private function getDashboardStats($staffProfile)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Attendance stats for current month
        $attendanceStats = $staffProfile->attendanceRecords()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();
        
        $presentDays = $attendanceStats->whereIn('status', ['present', 'late'])->count();
        
        // Leave balance (sum of all leave types)
        $leaveBalance = $this->calculateTotalLeaveBalance($staffProfile);
        
        // Pending tasks
        // $pendingTasks = Task::where('assigned_to', $staffProfile->id)
        //     ->where('status', '!=', 'completed')
        //     ->count();
        $pendingTasks = 0;
        // Next payday (assuming 25th of each month)
        $nextPayday = date('j', strtotime('25th of next month'));
        $nextPaydayDate = date('F j', strtotime('25th of next month'));
        
        return [
            'present_days' => $presentDays,
            'leave_balance' => $leaveBalance,
            'pending_tasks' => $pendingTasks,
            'next_payday' => $nextPayday,
            'next_payday_date' => $nextPaydayDate,
        ];
    }
    
    // Calculate total leave balance
    private function calculateTotalLeaveBalance($staffProfile)
    {
        $totalBalance = 0;
        $currentYear = now()->year;
        
        // This is a simplified version - you should implement based on your leave types
        $leaveTypes = \App\Models\LeaveType::active()->get();
        
        foreach ($leaveTypes as $type) {
            $usedDays = $staffProfile->leaveApplications()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', $currentYear)
                ->where('status', 'approved')
                ->sum('total_days');
            
            $remaining = max(0, $type->annual_entitlement - $usedDays);
            $totalBalance += $remaining;
        }
        
        return $totalBalance;
    }
    
    // Get leave balances by type
    private function getLeaveBalances($staffProfile)
    {
        $balances = [];
        $leaveTypes = \App\Models\LeaveType::active()->get();
        $currentYear = now()->year;
        
        foreach ($leaveTypes as $type) {
            $usedDays = $staffProfile->leaveApplications()
                ->where('leave_type_id', $type->id)
                ->whereYear('start_date', $currentYear)
                ->where('status', 'approved')
                ->sum('total_days');
            
            $remaining = max(0, $type->annual_entitlement - $usedDays);
            
            $balances[] = [
                'name' => $type->name,
                'total' => $type->annual_entitlement,
                'used' => $usedDays,
                'remaining' => $remaining,
                'color' => $this->getLeaveTypeColor($type->code),
            ];
        }
        
        return collect($balances);
    }
    
    // Get recent activity
    private function getRecentActivity($staffProfile)
    {
        // This is a placeholder - implement based on your activity logging system
        $activities = collect();
        
        // Add attendance activities
        $attendanceActivities = $staffProfile->attendanceRecords()
            ->whereDate('date', '>=', today()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($record) {
                return (object)[
                    'description' => $record->clock_out 
                        ? "Worked {$record->hours_worked} hours on {$record->date->format('M j')}" 
                        : "Clocked in at {$record->clock_in->format('h:i A')}",
                    'created_at' => $record->created_at,
                    'icon' => 'clock',
                    'color' => 'primary',
                ];
            });
        
        $activities = $activities->merge($attendanceActivities);
        
        // Add leave activities
        $leaveActivities = $staffProfile->leaveApplications()
            ->where('created_at', '>=', today()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($leave) {
                return (object)[
                    'description' => "Applied for {$leave->leaveType->name} leave",
                    'created_at' => $leave->created_at,
                    'icon' => 'umbrella-beach',
                    'color' => 'success',
                ];
            });
        
        $activities = $activities->merge($leaveActivities);
        
        // Sort by date
        return $activities->sortByDesc('created_at')->take(5);
    }
    
    // Get color for leave type
    private function getLeaveTypeColor($code)
    {
        return match($code) {
            'AL' => 'primary',    // Annual Leave
            'SL' => 'success',    // Sick Leave
            'ML' => 'info',       // Maternity Leave
            'PL' => 'warning',    // Paternity Leave
            'CL' => 'danger',     // Compassionate Leave
            default => 'secondary',
        };
    }
    
    // Clock in/out from dashboard
    public function clock(Request $request)
    {
        $request->validate([
            'action' => 'required|in:clock_in,clock_out',
        ]);
        
        $staffProfile = Auth::user()->staffProfile;
        
        if ($request->action === 'clock_in') {
            // Clock in logic
            $today = today();
            
            // Check if already clocked in
            $existing = $staffProfile->attendanceRecords()
                ->whereDate('date', $today)
                ->first();
                
            if ($existing && $existing->clock_in) {
                return redirect()->route('staff.dashboard')
                    ->with('error', 'You have already clocked in today.');
            }
            
            $now = now();
            $status = $now->format('H:i') > '09:00' ? 'late' : 'present';
            
            $staffProfile->attendanceRecords()->updateOrCreate(
                ['date' => $today],
                [
                    'clock_in' => $now,
                    'status' => $status,
                    'notes' => $status === 'late' ? 'Late arrival' : null,
                ]
            );
            
            return redirect()->route('staff.dashboard')
                ->with('success', 'Clocked in successfully at ' . $now->format('h:i A'));
        } else {
            // Clock out logic
            $today = today();
            
            $attendance = $staffProfile->attendanceRecords()
                ->whereDate('date', $today)
                ->first();
                
            if (!$attendance || !$attendance->clock_in) {
                return redirect()->route('staff.dashboard')
                    ->with('error', 'You must clock in before clocking out.');
            }
            
            if ($attendance->clock_out) {
                return redirect()->route('staff.dashboard')
                    ->with('error', 'You have already clocked out today.');
            }
            
            $now = now();
            $clockInTime = $attendance->clock_in;
            $hoursWorked = $clockInTime->diffInMinutes($now) / 60;
            
            $attendance->update([
                'clock_out' => $now,
                'hours_worked' => round($hoursWorked, 2),
            ]);
            
            return redirect()->route('staff.dashboard')
                ->with('success', 'Clocked out successfully. Worked ' . round($hoursWorked, 2) . ' hours today.');
        }
    }
}