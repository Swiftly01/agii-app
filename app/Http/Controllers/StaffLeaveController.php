<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffLeaveController extends Controller
{
    // Display leave dashboard
    public function dashboard()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        // Get leave balances
        $leaveBalances = $this->getLeaveBalances($staffProfile);
        
        // Get statistics
        $stats = $this->getLeaveStatistics($staffProfile);
        
        // Get recent leave applications
        $recentApplications = $staffProfile->leaveApplications()
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get upcoming holidays
        $upcomingHolidays = Holiday::upcoming(30)->get();
        
        // Get active leave types
        $leaveTypes = LeaveType::active()->get();
        
        return view('staff-portal.leave.dashboard', compact(
            'leaveBalances',
            'stats',
            'recentApplications',
            'upcomingHolidays',
            'leaveTypes'
        ));
    }
    
    // Apply for leave
    public function create()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        // Get available leave types
        $leaveTypes = LeaveType::active()->get();
        
        // Get leave balances
        $leaveBalances = $this->getLeaveBalances($staffProfile);
        
        // Get upcoming holidays
        $upcomingHolidays = Holiday::whereDate('date', '>=', today())
            ->orderBy('date')
            ->get()
            ->pluck('date')
            ->map(function($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        
        return view('staff-portal.leave.apply', compact(
            'leaveTypes',
            'leaveBalances',
            'upcomingHolidays'
        ));
    }
    
    // Store leave application
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:1000',
            'emergency_contact' => 'nullable|string',
            'handover_to' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,png',
        ]);
        
        $staffProfile = Auth::user()->staffProfile;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        
        // Calculate total days (excluding weekends and holidays)
        $totalDays = $this->calculateLeaveDays(
            $request->start_date,
            $request->end_date,
            $leaveType->allowed_days ?? null
        );
        
        // Check leave balance
        $balance = $this->getLeaveBalance($staffProfile, $leaveType);
        if ($totalDays > $balance['remaining']) {
            return back()->with('error', 
                "Insufficient leave balance. You have {$balance['remaining']} days remaining for {$leaveType->name}."
            );
        }
        
        // Check for overlapping leave applications
        $overlapping = $staffProfile->leaveApplications()
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
            
        if ($overlapping) {
            return back()->with('error', 'You already have approved or pending leave during this period.');
        }
        
        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('leave-attachments/' . $staffProfile->staff_id, 'private');
        }
        
        // Create leave application
        $leaveApplication = $staffProfile->leaveApplications()->create([
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'emergency_contact' => $request->emergency_contact,
            'handover_to' => $request->handover_to,
            'attachment_path' => $attachmentPath,
            'status' => $leaveType->requires_approval ? 'pending' : 'approved',
        ]);
        
        // If no approval required, automatically approve
        if (!$leaveType->requires_approval) {
            $leaveApplication->update([
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => 'Auto-approved (no approval required for this leave type)',
            ]);
            
            // Update attendance records
            $this->updateAttendanceForLeave($staffProfile, $leaveApplication);
        }
        
        // Log activity
        activity()
            ->performedOn($leaveApplication)
            ->causedBy(Auth::user())
            ->log('Applied for ' . $leaveType->name . ' leave from ' . 
                  $request->start_date . ' to ' . $request->end_date);
        
        return redirect()->route('staff.leave.dashboard')
            ->with('success', 'Leave application submitted successfully.');
    }
    
    // Quick apply for leave
    public function quickApply(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:tomorrow',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:500',
        ]);
        
        $staffProfile = Auth::user()->staffProfile;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        
        // Calculate total days
        $totalDays = $this->calculateLeaveDays(
            $request->start_date,
            $request->end_date,
            $leaveType->allowed_days ?? null
        );
        
        // Create leave application
        $staffProfile->leaveApplications()->create([
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => $leaveType->requires_approval ? 'pending' : 'approved',
        ]);
        
        return back()->with('success', 'Quick leave application submitted.');
    }
    
    // View leave application details
    public function view($id)
    {
        $leaveApplication = LeaveApplication::with(['leaveType', 'approver'])
            ->findOrFail($id);
        
        // Check authorization
        if ($leaveApplication->staff_profile_id !== Auth::user()->staffProfile->id) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('staff-portal.leave.view', compact('leaveApplication'));
    }
    
    // Cancel leave application
    public function cancel($id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        // Check authorization
        if ($leaveApplication->staff_profile_id !== Auth::user()->staffProfile->id) {
            abort(403, 'Unauthorized access.');
        }
        
        // Only allow cancellation if status is pending
        if ($leaveApplication->status !== 'pending') {
            return back()->with('error', 'Only pending leave applications can be cancelled.');
        }
        
        $leaveApplication->update(['status' => 'cancelled']);
        
        activity()
            ->performedOn($leaveApplication)
            ->causedBy(Auth::user())
            ->log('Cancelled leave application');
        
        return back()->with('success', 'Leave application cancelled successfully.');
    }
    
    // View leave history
    public function history()
    {
        $staffProfile = Auth::user()->staffProfile;
        
        $leaveApplications = $staffProfile->leaveApplications()
            ->with('leaveType')
            ->when(request('year'), function($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('type'), function($query, $typeId) {
                return $query->where('leave_type_id', $typeId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $leaveTypes = LeaveType::active()->get();
        
        return view('staff-portal.leave.history', compact(
            'leaveApplications',
            'leaveTypes'
        ));
    }
    
    // Download leave attachment
    public function downloadAttachment($id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        // Check authorization
        if ($leaveApplication->staff_profile_id !== Auth::user()->staffProfile->id) {
            abort(403, 'Unauthorized access.');
        }
        
        if (!$leaveApplication->attachment_path) {
            abort(404, 'Attachment not found.');
        }
        
        return response()->download(
            storage_path('app/private/' . $leaveApplication->attachment_path),
            'leave_attachment_' . $leaveApplication->id . '.' . 
            pathinfo($leaveApplication->attachment_path, PATHINFO_EXTENSION)
        );
    }
    
    // Get leave balances
    private function getLeaveBalances($staffProfile)
    {
        $balances = [];
        $leaveTypes = LeaveType::active()->get();
        
        foreach ($leaveTypes as $type) {
            $balance = $this->getLeaveBalance($staffProfile, $type);
            $balances[] = array_merge(['name' => $type->name], $balance);
        }
        
        return $balances;
    }
    
    // Get individual leave balance
    private function getLeaveBalance($staffProfile, $leaveType)
    {
        $currentYear = now()->year;
        
        // Get total approved leave for this year
        $usedDays = $staffProfile->leaveApplications()
            ->where('leave_type_id', $leaveType->id)
            ->whereYear('start_date', $currentYear)
            ->where('status', 'approved')
            ->sum('total_days');
        
        // Get carry forward from previous year
        $carryForward = 0;
        if ($leaveType->carry_forward) {
            $previousYear = $currentYear - 1;
            $previousYearBalance = $leaveType->annual_entitlement - 
                $staffProfile->leaveApplications()
                    ->where('leave_type_id', $leaveType->id)
                    ->whereYear('start_date', $previousYear)
                    ->where('status', 'approved')
                    ->sum('total_days');
            
            $carryForward = max(0, min($previousYearBalance, $leaveType->max_carry_forward ?? 0));
        }
        
        $totalEntitlement = $leaveType->annual_entitlement + $carryForward;
        $remaining = max(0, $totalEntitlement - $usedDays);
        
        return [
            'color' => $this->getLeaveTypeColor($leaveType->code),
            'total' => $totalEntitlement,
            'used' => $usedDays,
            'remaining' => $remaining,
            'carry_forward' => $carryForward,
        ];
    }
    
    // Get leave statistics
    private function getLeaveStatistics($staffProfile)
    {
        $currentYear = now()->year;
        
        return [
            'pending' => $staffProfile->leaveApplications()
                ->where('status', 'pending')
                ->count(),
            'approved' => $staffProfile->leaveApplications()
                ->whereYear('start_date', $currentYear)
                ->where('status', 'approved')
                ->count(),
            'upcoming' => $staffProfile->leaveApplications()
                ->where('start_date', '>=', today())
                ->where('status', 'approved')
                ->sum('total_days'),
            'total' => $staffProfile->leaveApplications()->count(),
        ];
    }
    
    // Calculate leave days excluding weekends and holidays
    private function calculateLeaveDays($startDate, $endDate, $allowedDays = null)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $totalDays = 0;
        
        // If allowed days specified, only count those days
        if ($allowedDays && is_array($allowedDays)) {
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if (in_array(strtolower($date->format('l')), $allowedDays)) {
                    // Check if holiday
                    $isHoliday = Holiday::whereDate('date', $date)->exists();
                    if (!$isHoliday) {
                        $totalDays++;
                    }
                }
            }
        } else {
            // Count all weekdays (Monday to Friday) excluding holidays
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if (!$date->isWeekend()) {
                    $isHoliday = Holiday::whereDate('date', $date)->exists();
                    if (!$isHoliday) {
                        $totalDays++;
                    }
                }
            }
        }
        
        return $totalDays;
    }
    
    // Update attendance records for approved leave
    private function updateAttendanceForLeave($staffProfile, $leaveApplication)
    {
        $startDate = Carbon::parse($leaveApplication->start_date);
        $endDate = Carbon::parse($leaveApplication->end_date);
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip weekends
            if (!$date->isWeekend()) {
                // Skip holidays
                $isHoliday = Holiday::whereDate('date', $date)->exists();
                if (!$isHoliday) {
                    AttendanceRecord::updateOrCreate(
                        [
                            'staff_profile_id' => $staffProfile->id,
                            'date' => $date->format('Y-m-d'),
                        ],
                        [
                            'status' => 'leave',
                            'notes' => 'On ' . $leaveApplication->leaveType->name . ' leave',
                        ]
                    );
                }
            }
        }
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
}