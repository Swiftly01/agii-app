<?php

// routes/web.php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmploymentApplicationController;

// Public routes
Route::get('/employment/apply', [EmploymentApplicationController::class, 'create'])->name('employment.apply');
Route::post('/employment/apply', [EmploymentApplicationController::class, 'store'])->name('employment.store');
Route::get('/employment/success/{id}', [EmploymentApplicationController::class, 'success'])->name('employment.application.success');

Route::get('/guarantor-form/{application}', [EmploymentApplicationController::class, 'guarantor_show'])
    ->name('guarantor.form');

Route::post('/guarantor-form/{application}', [EmploymentApplicationController::class, 'submitGuarantor'])
    ->name('guarantor.submit');
    
    
// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/employment/applications', [EmploymentApplicationController::class, 'index'])->name('employment.index');
    Route::get('/employment/applications/{application}', [EmploymentApplicationController::class, 'show'])->name('employment.show');
    Route::post('/employment/applications/{application}/status', [EmploymentApplicationController::class, 'updateStatus'])->name('employment.updateStatus');
    Route::get('/employment/applications/{application}/download/{type}', [EmploymentApplicationController::class, 'downloadDocument'])->name('employment.download');
    Route::post('/employment/applications/bulk-action', [EmploymentApplicationController::class, 'bulkAction'])->name('employment.bulk-action');
});