<?php

use App\Http\Controllers\BecomeSellerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/become-seller', [BecomeSellerController::class, 'create'])->name('become-seller');
    Route::post('/become-seller', [BecomeSellerController::class, 'store'])->name('become-seller.store');
});
