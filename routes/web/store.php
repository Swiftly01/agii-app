<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authenticated Store Management
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/stores/create', [StoreController::class, 'create'])
        ->name('stores.create');

    Route::post('/stores', [StoreController::class, 'store'])
        ->name('stores.store');

    Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])
        ->name('stores.edit');

    Route::put('/stores/{store}', [StoreController::class, 'update'])
        ->name('stores.update');

    Route::get('/stores/{store}', [StoreController::class, 'show'])
        ->name('stores.admin.show');
});

/*
|--------------------------------------------------------------------------
| Public Store Pages (by slug)
|--------------------------------------------------------------------------
*/
Route::get('/stores', [StoreController::class, 'index'])
    ->name('stores.index');

Route::get('/stores/{slug}', [StoreController::class, 'publicShow'])
    ->name('stores.show');
