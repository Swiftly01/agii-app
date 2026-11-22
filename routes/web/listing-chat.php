<?php

use App\Http\Controllers\ListingChatController;
use Illuminate\Support\Facades\Route;

// Buyer <-> seller chat scoped to a listing
Route::middleware('auth')->prefix('listing-chat')->name('listing-chat.')->group(function () {
    Route::get('/', [ListingChatController::class, 'inbox'])->name('inbox');
    Route::post('/product/{product}', [ListingChatController::class, 'start'])->name('start');
    Route::get('/{conversation}', [ListingChatController::class, 'show'])->whereNumber('conversation')->name('show');
    Route::post('/{conversation}/messages', [ListingChatController::class, 'send'])->whereNumber('conversation')->name('send');
    Route::get('/{conversation}/poll', [ListingChatController::class, 'poll'])->whereNumber('conversation')->name('poll');
});
