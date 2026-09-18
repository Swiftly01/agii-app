<?php

use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorServiceController;
use App\Http\Controllers\VendorAnalyticsController;
use App\Http\Controllers\VendorMessageController;
use App\Http\Controllers\VendorReviewController;
use App\Http\Controllers\VendorSettingController;
use App\Http\Middleware\CheckSubscription;
use Illuminate\Support\Facades\Route;

// Vendor Dashboard Routes
Route::middleware(['auth'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        // Dashboard (without subscription check)
        Route::get('/home', [VendorDashboardController::class, 'index'])->name('dashboard');

        // All other routes with subscription check
        Route::middleware([CheckSubscription::class])->group(function () {
            // Analytics
            Route::get('/analytics', [VendorAnalyticsController::class, 'index'])->name('analytics');
            Route::get('/analytics/performance', [VendorAnalyticsController::class, 'performance'])->name('analytics.performance');
            Route::get('/analytics/contacts', [VendorAnalyticsController::class, 'contacts'])->name('analytics.contacts');

            // Messages
            Route::get('/messages', [VendorMessageController::class, 'index'])->name('messages');
            Route::get('/messages/{contact}', [VendorMessageController::class, 'show'])->name('messages.show');
            Route::post('/messages/{contact}/reply', [VendorMessageController::class, 'reply'])->name('messages.reply');

            // Reviews
            Route::get('/reviews', [VendorReviewController::class, 'index'])->name('reviews');
            Route::get('/reviews/{review}', [VendorReviewController::class, 'show'])->name('reviews.show');
            Route::post('/reviews/{review}/response', [VendorReviewController::class, 'response'])->name('reviews.response');

            // Settings
            Route::get('/settings', [VendorSettingController::class, 'edit'])->name('settings');
            Route::put('/settings/profile', [VendorSettingController::class, 'updateProfile'])->name('settings.profile');
            Route::put('/settings/business', [VendorSettingController::class, 'updateBusiness'])->name('settings.business');
            Route::put('/settings/notifications', [VendorSettingController::class, 'updateNotifications'])->name('settings.notifications');

            // Adverts
            Route::get('/adverts', [VendorDashboardController::class, 'showAdvert'])->name('showadvert');
        });
    });
