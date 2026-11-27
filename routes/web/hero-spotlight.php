<?php

use App\Http\Controllers\SpotlightController;
use Illuminate\Support\Facades\Route;

// Approved vendors buy and manage their homepage hero spotlight
Route::middleware(['auth', 'role:vendor', 'vendor.approved'])
    ->prefix('spotlight')
    ->name('spotlight.')
    ->group(function () {
        Route::get('/', [SpotlightController::class, 'show'])->name('show');
        Route::post('/checkout', [SpotlightController::class, 'checkout'])->name('checkout');
        Route::get('/callback', [SpotlightController::class, 'callback'])->name('callback');
        Route::put('/product', [SpotlightController::class, 'changeProduct'])->name('product');
    });
