<?php

use App\Http\Controllers\MarketerRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register/marketer', [MarketerRegisterController::class, 'create'])->name('marketer.register');
    Route::post('/register/marketer', [MarketerRegisterController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('marketer.register.store');
});
