<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketerController;

Route::middleware(['auth'])->group(function () {
    // Marketer routes
    Route::prefix('marketer')->group(function () {
        Route::get('/dashboard', [MarketerController::class, 'dashboard'])->name('marketer.dashboard');

        // Vendor management
        Route::get('/vendors/create', [MarketerController::class, 'createVendor'])->name('marketer.vendors.create');
        Route::post('/vendors', [MarketerController::class, 'storeVendor'])->name('marketer.vendors.store');

        // Payment routes
        Route::get('/vendors/{vendor}/payment', [MarketerController::class, 'showVendorPayment'])->name('marketer.vendors.payment');
        Route::get('/vendors/{vendor}/checkout/{plan}', [MarketerController::class, 'showCheckout'])->name('marketer.vendors.checkout');
    
    
        Route::post('/vendors/{vendor}/payment/store', [MarketerController::class, 'processVendorPayment'])->name('marketer.vendors.process-payment');
        
        Route::get('/payment/callback', [MarketerController::class, 'handlePaymentCallback'])->name('marketer.payment.callback');

        Route::get('/vendors/{vendor}', [MarketerController::class, 'showVendor'])->name('marketer.vendors.show');

        // Product management
        Route::get('/vendors/{vendor}/products/create', [MarketerController::class, 'createProduct'])->name('marketer.products.create');
        Route::post('/vendors/{vendor}/products', [MarketerController::class, 'storeProduct'])->name('marketer.products.store');

        // Product show and update routes (using your preferred structure)
        Route::get('/show-product/{product}', [MarketerController::class, 'showProduct'])->name('marketer.products.show');
        Route::put('/update-product/{product}', [MarketerController::class, 'updateProduct'])->name('marketer.products.update');

        // Withdrawal route
        Route::post('/withdraw', [MarketerController::class, 'requestWithdrawal'])->name('marketer.withdraw.request');
    });


    Route::get('/vendorslist', [MarketerController::class, 'showVendorList'])->name('marketer.vendors.index');
});
