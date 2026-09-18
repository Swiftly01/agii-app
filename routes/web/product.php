<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckSubscription;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// HOME PAGE (optional category filter)
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/home/{category?}', [ProductController::class, 'homeByCategory'])
    ->where('category', '[A-Za-z0-9\-]+')
    ->name('home.category');

// PRODUCTS LISTING
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{category}', [ProductController::class, 'index'])
    ->where('category', '.*')
    ->name('products.category');

// SINGLE PRODUCT PAGE
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// PRODUCT TRACKING & INQUIRIES
Route::post('/products/track-view', [ProductController::class, 'trackView'])->name('products.track-view');
Route::get('/products/{product}/inquiries-count', [ProductController::class, 'getInquiriesCount'])->name('products.inquiries-count');

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// CONTACT SELLER (AUTH REQUIRED)
Route::middleware(['auth'])->post('/products/contact', [ProductController::class, 'storeContact'])
    ->name('contacts.store');


// CONFIRMATION AFTER ADDING PRODUCT
Route::get('/products-added/{id}', [ProductController::class, 'added'])->name('products.added');

// USER HOTELS
Route::get('/user/{user}/hotels', [ProductController::class, 'userHotels'])->name('user.hotels');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (SELLERS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::middleware([CheckSubscription::class])->group(function () {
        // CREATE PRODUCT
        Route::get('/sell/{type}', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');

        // EDIT / UPDATE / DELETE (MUST COME BEFORE wildcard category route)
        Route::get('/products/{slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{slug}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // AJAX CATEGORY / SPECIFICATION
    Route::get('/categories/{category}/subcategories', [ProductController::class, 'getSubcategories']);
    Route::get('/categories/{category}/specifications', [ProductController::class, 'getSpecifications']);
});

/*
|--------------------------------------------------------------------------
| SERVICE ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/services', [ProductController::class, 'index'])->name('services.index');
Route::get('/services/{category}', [ProductController::class, 'index'])->name('services.category');
