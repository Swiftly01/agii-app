<?php

use App\Http\Controllers\BoostSubscriptionController;
use App\Http\Controllers\ProductBoostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/boost/plans', [BoostSubscriptionController::class, 'show'])->name('boost.plans');
    Route::post('/boost/checkout', [BoostSubscriptionController::class, 'checkout'])->name('boost.checkout');
    Route::get('/boost/callback', [BoostSubscriptionController::class, 'callback'])->name('boost.callback');

    Route::post('/products/{product}/boost', [ProductBoostController::class, 'store'])->name('products.boost');
    Route::delete('/products/{product}/boost', [ProductBoostController::class, 'destroy'])->name('products.unboost');
    Route::post('/products/{product}/boost/carousel-pick', [ProductBoostController::class, 'setCarouselPick'])->name('products.boost.carousel-pick');
});

// Paystack calls this directly — no session/auth available.
Route::post('/boost/webhook', [BoostSubscriptionController::class, 'webhook'])->name('boost.webhook');
