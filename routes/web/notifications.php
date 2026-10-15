<?php

use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('notifications/push')->name('push.')->group(function () {
    Route::post('subscribe', [PushSubscriptionController::class, 'store'])->name('subscribe');
    Route::delete('subscribe', [PushSubscriptionController::class, 'destroy'])->name('unsubscribe');
});
