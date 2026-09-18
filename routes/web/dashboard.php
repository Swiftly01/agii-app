<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::middleware(['auth', 'verified'])->group(function () {
 

    Route::get('/customer/dashboard', [DashboardController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/dashboard', [DashboardController::class, 'customerDashboard'])->name('dashboard');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('contacts.store');
    Route::put('/contacts/update-outcome', [DashboardController::class, 'updateOutcome'])->name('contacts.update-outcome');
    Route::get('/vendors/{vendor}/products', [DashboardController::class, 'getVendorProducts'])->name('vendors.products');
});