<?php

use App\Http\Controllers\MarketerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Remove the "action:" keyword and fix the route URLs
    Route::post('marketer/products/{product}/approve', [MarketerController::class, 'approve'])->name('marketer.products.approve');
    Route::post('marketer/products/{product}/disapprove', [MarketerController::class, 'disapprove'])->name('marketer.products.disapprove');
});
