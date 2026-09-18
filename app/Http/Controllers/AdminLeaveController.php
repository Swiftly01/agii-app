<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLeaveController extends Controller
{
    // Display leave applications dashboard
    public function index()
    {
        $status = request('status', 'pending');
        $department = request('department');
        $month = request('month', date('m'));
        $year = request('year', date('Y'));
        
        $query = LeaveApplication::with(['staffProfile.user', 'leaveType', 'approver'])
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($department, function($query, $department) {
                return $query->whereHas('staffProfile', function($q) use ($department) {
                    $q->where('department', $department);
                });
            })
            ->when($month, function($query, $month) {
                return $query->whereMonth('created_at', $month);
            })
            ->when($year, function($query, $year) {
                return $query->whereYear('created_at', $year);
            });
        
        $leaveApplications = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get statistics
        $stats = $this->getLeaveStatistics();
        
        // Get departments for filter
        $departments = StaffProfile::distinct('department')->pluck('department');
        
        // Get leave types
        $leaveTypes = LeaveType::active()->get();
        
        return view('admin.leave.dashboard', compact(
            'leaveApplications',
            'stats',
            'departments',
            'leaveTypes',
            'status',
            'department',
            'month',
            'year'
        ));
    }
    
    // View leave application details
    public function view($id)
    {
        $leaveApplication = LeaveApplication::with([
            'staffProfile.user', 
            'leaveType', 
            'approver'
        ])->findOrFail($id);
        
        return view('admin.leave.view', compact('leaveApplication'));
    }
    
    // Approve leave application
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);
        
        $leaveApplication = LeaveApplication::with('staffProfile')->findOrFail($id);
        
        if ($leaveApplication->status !== 'pending') {
            return back()->with('error', 'This leave application has already been processed.');
        }
        
        // Check leave balance
        $balance = $this->checkLeaveBalance($leaveApplication);
        if (!$balance['has_sufficient']) {
            return back()->with('error', 
                "Insufficient leave balance. Staff has only {$balance['remaining']} days remaining."
            );
        }
        
        $leaveApplication->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);
        
        // Update attendance records
        $this->updateAttendanceForLeave($leaveApplication);
        
        // Log activity
        activity()
            ->performedOn($leaveApplication)
            ->causedBy(auth()->user())
            ->log('Approved leave application');
        
        return back()->with('success', 'Leave application approved successfully.');
    }
    
    // Reject leave application
    public function reject(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'required|string|min:10|max:500',
        ]);
        
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        if ($leaveApplication->status !== 'pending') {
            return back()->with('error', 'This leave application has already been processed.');
        }
        
        $leaveApplication->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);
        
        activity()
            ->performedOn($leaveApplication)
            ->causedBy(auth()->user())
            ->log('Rejected leave application');
        
        return back()->with('success', 'Leave application rejected.');
    }
    
    // Cancel approved leave
    public function cancel($id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);
        
        if ($leaveApplication->status !== 'approved') {
            return back()->with('error', 'Only approved leave applications can be cancelled.');
        }
        
        if ($leaveApplication->start_date <= today()) {
            return back()->with('error', 'Cannot cancel leave that has already started.');
        }
        
        $leaveApplication->update(['status' => 'cancelled']);
        
        // Remove leave from attendance records
        $this->removeLeaveFromAttendance($leaveApplication);
        
        activity()
            ->performedOn($leaveApplication)
            ->causedBy(auth()->user())
            ->log('Cancelled approved leave application');
        
        return back()->with('success', 'Leave application cancelled successfully.');
    }
    
    // Manage leave types
    public function manageTypes()
    {
        $leaveTypes = LeaveType::orderBy('sort_order')->paginate(20);
        
        return view('admin.leave.types', compact('leaveTypes'));
    }
    
    // Create leave type
    public function createType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types',
            'code' => 'required|string|max:10|unique:leave_types',
            'annual_entitlement' => 'required|integer|min:0',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'nullable|integer|min:0',
            'requires_approval' => 'boolean',
            'allowed_days' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ]);
        
        LeaveType::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'annual_entitlement' => $request->annual_entitlement,
            'carry_forward' => $request->carry_forward ?? false,
            'max_carry_forward' => $request->max_carry_forward,
            'requires_approval' => $request->requires_approval ?? true,
            'allowed_days' => $request->allowed_days,
            'description' => $request->description,
            'sort_order' => LeaveType::max('sort_order') + 1,
        ]);
        
        return back()->with('success', 'Leave type created successfully.');
    }
    
    // Update leave type
    public function updateType(Request $request, $id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $id,
            'code' => 'required|string|max:10|unique:leave_types,code,' . $id,
            'annual_entitlement' => 'required|integer|min:0',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'nullable|integer|min:0',
            'requires_approval' => 'boolean',
            'allowed_days' => 'nullable|array',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $leaveType->update($request->all());
        
        return back()->with('success', 'Leave type updated successfully.');
    }
    
    // Delete leave type
    public function deleteType($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        
        // Check if leave type is used in applications
        if ($leaveType->leaveApplications()->exists()) {
            return back()->with('error', 'Cannot delete leave type that has existing applications.');
        }
        
        $leaveType->delete();
        
        return back()->with('success', 'Leave type deleted successfully.');
    }
    
    // Get leave statistics
    private function getLeaveStatistics()
    {
        $currentYear = now()->year;
        
        return [
            'pending' => LeaveApplication::where('status', 'pending')->count(),
            'approved_today' => LeaveApplication::whereDate('approved_at', today())
                ->where('status', 'approved')
                ->count(),
            'total_this_month' => LeaveApplication::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_this_year' => LeaveApplication::whereYear('created_at', $currentYear)->count(),
            'by_type' => LeaveApplication::selectRaw('
                    leave_types.name, 
                    COUNT(*) as count,
                    SUM(total_days) as total_days
                ')
                ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.id')
                ->whereYear('leave_applications.created_at', $currentYear)
                ->where('leave_applications.status', 'approved')
                ->groupBy('leave_types.name')
                ->get(),
        ];
    }
    
    // Check leave balance
    private function checkLeaveBalance($leaveApplication)
    {
        $staffProfile = $leaveApplication->staffProfile;
        $leaveType = $leaveApplication->leaveType;
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
            'has_sufficient' => $leaveApplication->total_days <= $remaining,
            'remaining' => $remaining,
            'total_entitlement' => $totalEntitlement,
            'used' => $usedDays,
            'carry_forward' => $carryForward,
        ];
    }
    
    // Update attendance records for approved leave
    private function updateAttendanceForLeave($leaveApplication)
    {
        $staffProfile = $leaveApplication->staffProfile;
        $startDate = Carbon::parse($leaveApplication->start_date);
        $endDate = Carbon::parse($leaveApplication->end_date);
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip weekends
            if (!$date->isWeekend()) {
                \App\Models\AttendanceRecord::updateOrCreate(
                    [
                        'staff_profile_id' => $staffProfile->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => 'leave',
                        'notes' => 'On ' . $leaveApplication->leaveType->name . ' leave (Approved)',
                    ]
                );
            }
        }
    }
    
    // Remove leave from attendance records
    private function removeLeaveFromAttendance($leaveApplication)
    {
        $staffProfile = $leaveApplication->staffProfile;
        $startDate = Carbon::parse($leaveApplication->start_date);
        $endDate = Carbon::parse($leaveApplication->end_date);
        
        \App\Models\AttendanceRecord::where('staff_profile_id', $staffProfile->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'leave')
            ->delete();
    }
    
    // Export leave data
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'format' => 'required|in:csv,excel',
        ]);
        
        $leaveApplications = LeaveApplication::with(['staffProfile.user', 'leaveType', 'approver'])
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->orderBy('created_at')
            ->get();
        
        if ($request->format === 'csv') {
            return $this->exportToCsv($leaveApplications);
        }
        
        return back()->with('error', 'Export format not yet implemented.');
    }
    
    // Export to CSV
    private function exportToCsv($records)
    {
        $filename = 'leave_export_' . date('Y-m-d_H-i-s') . '.csv';
        
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
                'Staff ID', 'Name', 'Leave Type', 'Start Date', 'End Date', 
                'Total Days', 'Reason', 'Status', 'Applied On', 'Approved By', 
                'Approval Notes'
            ]);
            
            // Data
            foreach ($records as $record) {
                fputcsv($file, [
                    $record->staffProfile->staff_id ?? 'N/A',
                    $record->staffProfile->user->name ?? 'N/A',
                    $record->leaveType->name,
                    $record->start_date->format('Y-m-d'),
                    $record->end_date->format('Y-m-d'),
                    $record->total_days,
                    $record->reason,
                    $record->status,
                    $record->created_at->format('Y-m-d H:i:s'),
                    $record->approver->name ?? 'N/A',
                    $record->approval_notes ?? '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}