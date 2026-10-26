<?php

use App\Http\Controllers\PhoneRegisterController;
use App\Http\Controllers\PhoneVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register/phone', [PhoneRegisterController::class, 'create'])->name('phone.register');
    Route::post('/register/phone', [PhoneRegisterController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('phone.register.store');
});

Route::middleware('auth')->prefix('phone/verify')->name('phone.verification.')->group(function () {
    Route::get('/', [PhoneVerificationController::class, 'notice'])->name('notice');
    Route::post('/', [PhoneVerificationController::class, 'verify'])->middleware('throttle:10,1')->name('verify');
    Route::post('/resend', [PhoneVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('resend');
});
