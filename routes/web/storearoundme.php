<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StoreAroundMeController;

// Store routes for both guests and authenticated users
Route::get('/store-around-me', [App\Http\Controllers\StoreAroundMeController::class, 'store_users_vendors_me'])->name('store.index');
Route::get('/store/category/{category}', [App\Http\Controllers\StoreAroundMeController::class, 'store_users_vendors_me'])->name('store.category');

// Location routes
Route::post('/store-location', [App\Http\Controllers\StoreAroundMeController::class, 'storeUserLocation'])->name('store.location');
Route::get('/store-location', [App\Http\Controllers\StoreAroundMeController::class, 'storeUserLocation'])->name('store.location');

Route::get('/vendors/lga/{lga}', [App\Http\Controllers\StoreAroundMeController::class, 'getVendorsByLGA'])->name('vendors.by_lga');
Route::get('/search/locations', [App\Http\Controllers\StoreAroundMeController::class, 'searchVendorsByLocation'])->name('search.locations');

Route::get('/stores-around-me/{vendor}', [StoreAroundMeController::class, 'show'])->name('store.show');
