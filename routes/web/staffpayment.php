<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffLeaveController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminPayrollController;
use App\Http\Controllers\StaffPayrollController;


// Staff Portal Routes
Route::middleware(['auth'])->prefix('my')->name('staff.')->group(function () {
    // Attendance
    Route::get('/attendance', [StaffAttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::post('/attendance/clock', [StaffAttendanceController::class, 'clock'])->name('attendance.clock');
    Route::get('/attendance/history', [StaffAttendanceController::class, 'history'])->name('attendance.history');
    
    // Leave
    Route::get('/leave', [StaffLeaveController::class, 'dashboard'])->name('leave.dashboard');
    Route::get('/leave/apply', [StaffLeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave', [StaffLeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/history', [StaffLeaveController::class, 'index'])->name('leave.history');
    
    // Payroll
    Route::get('/payroll', [StaffPayrollController::class, 'dashboard'])->name('payroll.dashboard');
    Route::get('/payroll/payslip/{id}', [StaffPayrollController::class, 'payslip'])->name('payroll.payslip');
    Route::get('/payroll/history', [StaffPayrollController::class, 'history'])->name('payroll.history');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Attendance Management
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/bulk-update', [AdminAttendanceController::class, 'bulkUpdate'])->name('attendance.bulk-update');
    
    // Leave Management
    Route::get('/leave/applications', [AdminLeaveController::class, 'index'])->name('leave.applications');
    Route::post('/leave/{id}/approve', [AdminLeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [AdminLeaveController::class, 'reject'])->name('leave.reject');
    
    // Payroll Management
    Route::get('/payroll', [AdminPayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/run', [AdminPayrollController::class, 'run'])->name('payroll.run');
    Route::post('/payroll/process-payment', [AdminPayrollController::class, 'processPayment'])->name('payroll.process-payment');
});

// routes/web.php

// Staff Attendance Routes
Route::middleware(['auth'])->prefix('my')->name('staff.')->group(function () {
    // Attendance
    Route::get('/attendance', [StaffAttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::post('/attendance/clock', [StaffAttendanceController::class, 'clock'])->name('attendance.clock');
    Route::get('/attendance/history', [StaffAttendanceController::class, 'history'])->name('attendance.history');
    
    // Leave
    Route::get('/leave', [StaffLeaveController::class, 'dashboard'])->name('leave.dashboard');
    Route::get('/leave/apply', [StaffLeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave', [StaffLeaveController::class, 'store'])->name('leave.store');
    Route::post('/leave/quick-apply', [StaffLeaveController::class, 'quickApply'])->name('leave.quick-apply');
    Route::get('/leave/history', [StaffLeaveController::class, 'history'])->name('leave.history');
    Route::get('/leave/{id}', [StaffLeaveController::class, 'view'])->name('leave.view');
    Route::post('/leave/{id}/cancel', [StaffLeaveController::class, 'cancel'])->name('leave.cancel');
    Route::get('/leave/{id}/download', [StaffLeaveController::class, 'downloadAttachment'])->name('leave.download');
});


// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Attendance Management
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staff'])->name('attendance.staff');
    Route::put('/attendance/update', [AdminAttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendance/bulk-update', [AdminAttendanceController::class, 'bulkUpdate'])->name('attendance.bulk-update');
    Route::post('/attendance/mark-holiday', [AdminAttendanceController::class, 'markHoliday'])->name('attendance.mark-holiday');
    Route::get('/attendance/export', [AdminAttendanceController::class, 'export'])->name('attendance.export');
    Route::get('/attendance/report', [AdminAttendanceController::class, 'report'])->name('attendance.report');
    
    // Leave Management
    Route::get('/leave/applications', [AdminLeaveController::class, 'index'])->name('leave.applications');
    Route::get('/leave/{id}', [AdminLeaveController::class, 'view'])->name('leave.view');
    Route::post('/leave/{id}/approve', [AdminLeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [AdminLeaveController::class, 'reject'])->name('leave.reject');
    Route::post('/leave/{id}/cancel', [AdminLeaveController::class, 'cancel'])->name('leave.cancel');
    Route::get('/leave/types', [AdminLeaveController::class, 'manageTypes'])->name('leave.types');
    Route::post('/leave/types', [AdminLeaveController::class, 'createType'])->name('leave.types.store');
    Route::put('/leave/types/{id}', [AdminLeaveController::class, 'updateType'])->name('leave.types.update');
    Route::delete('/leave/types/{id}', [AdminLeaveController::class, 'deleteType'])->name('leave.types.delete');
    Route::get('/leave/export', [AdminLeaveController::class, 'export'])->name('leave.export');
    
    // Holiday Management
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
    Route::post('/holidays/import', [HolidayController::class, 'import'])->name('holidays.import');
    Route::get('/holidays/export', [HolidayController::class, 'export'])->name('holidays.export');
    Route::get('/holidays/calendar', [HolidayController::class, 'calendar'])->name('holidays.calendar');
});