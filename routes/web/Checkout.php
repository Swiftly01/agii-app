<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentPlanController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
// Payment Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Checkout routes
    Route::get('/vendor/checkout/{plan}', [CheckoutController::class, 'checkout'])->name('vendor.checkout');

    // Initialize Paystack payment
    Route::post('/vendor/initialize-payment', [CheckoutController::class, 'initializePayment'])->name('vendor.initialize-payment');

    // Payment callback
    Route::get('/payment/callback', [CheckoutController::class, 'handleCallback'])->name('payment.callback');

    // Paystack webhook (for server-to-server notifications)
    Route::post('/payment/webhook', [CheckoutController::class, 'handleWebhook'])->name('payment.webhook');
});

// Payment Plans Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Show payment plans for vendors
    Route::get('/vendor/plans', [PaymentPlanController::class, 'showPlans'])->name('vendor.plans');

    // Select plan and go to checkout
    Route::get('/vendor/plan/{plan}/select', [PaymentPlanController::class, 'selectPlan'])->name('vendor.plan.select');

    // Checkout routes
    Route::get('/vendor/checkout/{plan}', [CheckoutController::class, 'checkout'])->name('vendor.checkout');
    Route::post('/vendor/process-payment', [CheckoutController::class, 'processPayment'])->name('vendor.process-payment');
});

// Update dashboard routes to require subscription
// Route::middleware(['auth', 'verified', 'vendor.subscription'])->group(function () {
//     Route::get('/vendor/dashboard', [DashboardController::class, 'index'])->name('vendor.dashboard');
// });
