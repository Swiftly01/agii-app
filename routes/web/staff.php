<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffLeaveController;
use App\Http\Controllers\StaffDocumentController;
use App\Http\Controllers\StaffOfferLetterController;

use App\Http\Controllers\AdminDocumentController;
use App\Http\Controllers\AdminOfferLetterController;

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return redirect()->route('staff.dashboard');
});

/*
|--------------------------------------------------------------------------
| STAFF PORTAL ROUTES (NO NAME CHANGES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::post('/clock', [StaffDashboardController::class, 'clock'])->name('attendance.clock');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StaffProfileController::class, 'view'])->name('view');
        Route::get('/edit', [StaffProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [StaffProfileController::class, 'update'])->name('update');
        Route::get('/documents', [StaffProfileController::class, 'documents'])->name('documents');
    });

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [StaffAttendanceController::class, 'dashboard'])->name('dashboard');
        Route::post('/clock', [StaffAttendanceController::class, 'clock'])->name('clock');
        Route::get('/history', [StaffAttendanceController::class, 'history'])->name('history');
    });

    // Leave
    Route::prefix('leave')->name('leave.')->group(function () {
        Route::get('/', [StaffLeaveController::class, 'dashboard'])->name('dashboard');
        Route::get('/apply', [StaffLeaveController::class, 'create'])->name('apply');
        Route::post('/', [StaffLeaveController::class, 'store'])->name('store');
        Route::get('/history', [StaffLeaveController::class, 'history'])->name('history');
        Route::get('/{id}', [StaffLeaveController::class, 'view'])->name('view');
        Route::post('/{id}/cancel', [StaffLeaveController::class, 'cancel'])->name('cancel');
    });

    // Documents (UPLOAD NAME PRESERVED)
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [StaffDocumentController::class, 'index'])->name('my-documents');
        Route::get('/upload', [StaffDocumentController::class, 'create'])->name('upload');
        Route::post('/', [StaffDocumentController::class, 'store'])->name('store');
        Route::get('/certificates', [StaffDocumentController::class, 'certificates'])->name('certificates');
        Route::get('/contracts', [StaffDocumentController::class, 'contracts'])->name('contracts');
        Route::get('/{id}/download', [StaffDocumentController::class, 'download'])->name('download');
        Route::get('/{id}/view', [StaffDocumentController::class, 'view'])->name('view');
    });

    // Offer Letters
    Route::prefix('offer-letters')->name('offer-letters.')->group(function () {
        Route::get('/', [StaffOfferLetterController::class, 'myOfferLetters'])->name('index');
        Route::get('/{id}/download', [StaffOfferLetterController::class, 'download'])->name('download');
        Route::get('/{id}/view', [StaffOfferLetterController::class, 'view'])->name('view');
    });
});

Route::get('/admin/staff/{staffProfile}/documents', [StaffDocumentController::class, 'index'])->name('admin.staff.documents');


/*
|--------------------------------------------------------------------------
| ADMIN – STAFF MANAGEMENT (NO COLLISIONS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin/staff')
    ->name('admin.staff.')
    ->group(function () {

    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/create-from-application/{id}', [StaffController::class, 'createFromApplication'])->name('create-from-application');
    Route::post('/', [StaffController::class, 'store'])->name('store');
    // Route::get('/{id}', [StaffController::class, 'show'])->name('show');
    // Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('edit');
    // Route::put('/{id}', [StaffController::class, 'update'])->name('update');
    
});

Route::middleware('auth')->group(function () {
// In your web.php routes file
Route::get('admin/staff/{id}/edit-from-application', [StaffController::class, 'editFromApplication'])->name('admin.staff.edit-from-application');
Route::put('admin/staff/update-from-application/{id}', [StaffController::class, 'updateFromApplication'])->name('admin.staff.update-from-application');

});


/*
|--------------------------------------------------------------------------
| ADMIN – DOCUMENT MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/documents/pending', [AdminDocumentController::class, 'pending'])->name('documents.pending');
    Route::get('/documents/all', [AdminDocumentController::class, 'allDocuments'])->name('documents.all');
    Route::get('/documents/{id}/review', [AdminDocumentController::class, 'review'])->name('documents.review');
    Route::put('/documents/{id}/status', [AdminDocumentController::class, 'updateStatus'])->name('documents.updateStatus');
    
        // Bulk actions
    Route::post('/bulk-approve', [AdminDocumentController::class, 'bulkApprove'])->name('bulk-approve');
    Route::post('/bulk-reject', [AdminDocumentController::class, 'bulkReject'])->name('bulk-reject');
    Route::post('/bulk-action', [AdminDocumentController::class, 'bulkAction'])->name('bulk-action');
    
});

/*
|--------------------------------------------------------------------------
| ADMIN – OFFER LETTERS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin/offer-letters')
    ->name('admin.offer-letters.')
    ->group(function () {

    Route::get('/', [AdminOfferLetterController::class, 'index'])->name('index');
    Route::get('/send', [AdminOfferLetterController::class, 'create'])->name('create');
    Route::post('/send', [AdminOfferLetterController::class, 'sendToSelected'])->name('send-selected');
    Route::post('/send-all', [AdminOfferLetterController::class, 'sendToAll'])->name('send-all');
    Route::get('/{id}/download', [AdminOfferLetterController::class, 'download'])->name('download');
    Route::get('/{id}/preview', [AdminOfferLetterController::class, 'preview'])->name('preview');
    Route::delete('/{id}', [AdminOfferLetterController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| API-LIKE ROUTE (LEFT AS-IS)
|--------------------------------------------------------------------------
*/
Route::get('/api/staff/department-count/{department}', [
    StaffController::class,
    'getDepartmentCount'
]);
