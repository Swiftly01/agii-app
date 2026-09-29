<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffLeaveController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\AdminPayrollController;
use App\Http\Controllers\StaffPayrollController;

/*
|--------------------------------------------------------------------------
| Staff extras + HR admin + payroll
|--------------------------------------------------------------------------
| The staff attendance / leave routes that used to be duplicated here now live
| ONLY in staff.php (duplicate route names made the target URL depend on file
| load order). Only routes that exist nowhere else are kept in this file.
*/

// ---------------- Staff (my) ----------------
Route::middleware(['auth'])->prefix('my')->name('staff.')->group(function () {
    // Leave extras
    Route::post('/leave/quick-apply', [StaffLeaveController::class, 'quickApply'])->name('leave.quick-apply');
    Route::get('/leave/{id}/download', [StaffLeaveController::class, 'downloadAttachment'])->name('leave.download');

    // Payroll: StaffPayrollController hasn't been built yet. Until it exists, the
    // links redirect back with a message instead of throwing a 500.
    if (class_exists(StaffPayrollController::class)) {
        Route::get('/payroll', [StaffPayrollController::class, 'dashboard'])->name('payroll.dashboard');
        Route::get('/payroll/payslip/{id}', [StaffPayrollController::class, 'payslip'])->name('payroll.payslip');
        Route::get('/payroll/history', [StaffPayrollController::class, 'history'])->name('payroll.history');
    } else {
        $payrollUnavailable = fn () => redirect()->route('staff.dashboard')
            ->with('error', 'Payroll is not available yet.');
        Route::get('/payroll', $payrollUnavailable)->name('payroll.dashboard');
        Route::get('/payroll/payslip/{id}', $payrollUnavailable)->name('payroll.payslip');
        Route::get('/payroll/history', $payrollUnavailable)->name('payroll.history');
    }
});

// ---------------- Admin (HR) ----------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Attendance Management
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staff'])->name('attendance.staff');
    Route::put('/attendance/update', [AdminAttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendance/bulk-update', [AdminAttendanceController::class, 'bulkUpdate'])->name('attendance.bulk-update');
    Route::post('/attendance/mark-holiday', [AdminAttendanceController::class, 'markHoliday'])->name('attendance.mark-holiday');
    Route::get('/attendance/export', [AdminAttendanceController::class, 'export'])->name('attendance.export');
    Route::get('/attendance/report', [AdminAttendanceController::class, 'report'])->name('attendance.report');

    // Leave Management (fixed-path routes BEFORE the /leave/{id} wildcard)
    Route::get('/leave/applications', [AdminLeaveController::class, 'index'])->name('leave.applications');
    Route::get('/leave/types', [AdminLeaveController::class, 'manageTypes'])->name('leave.types');
    Route::post('/leave/types', [AdminLeaveController::class, 'createType'])->name('leave.types.store');
    Route::put('/leave/types/{id}', [AdminLeaveController::class, 'updateType'])->name('leave.types.update');
    Route::delete('/leave/types/{id}', [AdminLeaveController::class, 'deleteType'])->name('leave.types.delete');
    Route::get('/leave/export', [AdminLeaveController::class, 'export'])->name('leave.export');
    Route::get('/leave/{id}', [AdminLeaveController::class, 'view'])->name('leave.view');
    Route::post('/leave/{id}/approve', [AdminLeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [AdminLeaveController::class, 'reject'])->name('leave.reject');
    Route::post('/leave/{id}/cancel', [AdminLeaveController::class, 'cancel'])->name('leave.cancel');

    // Holiday Management
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
    Route::post('/holidays/import', [HolidayController::class, 'import'])->name('holidays.import');
    Route::get('/holidays/export', [HolidayController::class, 'export'])->name('holidays.export');
    Route::get('/holidays/calendar', [HolidayController::class, 'calendar'])->name('holidays.calendar');

    // Payroll Management: AdminPayrollController hasn't been built yet
    if (class_exists(AdminPayrollController::class)) {
        Route::get('/payroll', [AdminPayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/run', [AdminPayrollController::class, 'run'])->name('payroll.run');
        Route::post('/payroll/process-payment', [AdminPayrollController::class, 'processPayment'])->name('payroll.process-payment');
    } else {
        $adminPayrollUnavailable = fn () => redirect()->route('admin.dashboard')
            ->with('error', 'Payroll management is not available yet.');
        Route::get('/payroll', $adminPayrollUnavailable)->name('payroll.index');
        Route::post('/payroll/run', $adminPayrollUnavailable)->name('payroll.run');
        Route::post('/payroll/process-payment', $adminPayrollUnavailable)->name('payroll.process-payment');
    }
});
