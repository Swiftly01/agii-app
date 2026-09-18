<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HrQueryController;


// Admin/HR Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/hr-queries', [HrQueryController::class, 'index'])->name('admin.hr-queries.index');
        Route::post('/hr-queries', [HrQueryController::class, 'storeByHr'])->name('admin.hr-queries.store');
        Route::post('/hr-queries/{id}/respond', [HrQueryController::class, 'respondAsHr'])->name('admin.hr-queries.respond');
        Route::post('/hr-queries/{id}/close', [HrQueryController::class, 'close'])->name('admin.hr-queries.close');
        Route::get('/hr-queries/{id}', [HrQueryController::class, 'showForHr'])->name('admin.hr-queries.show');
    });
});

// Staff Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-hr-queries', [HrQueryController::class, 'myQueries'])->name('staff.hr-queries.index');
    Route::post('/hr-queries', [HrQueryController::class, 'storeByStaff'])->name('staff.hr-queries.store');
    Route::post('/hr-queries/{id}/respond', [HrQueryController::class, 'respondAsStaff'])->name('staff.hr-queries.respond');
    Route::get('/hr-queries/{id}', [HrQueryController::class, 'showForStaff'])->name('staff.hr-queries.show');
});