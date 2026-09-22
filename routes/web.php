<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Routes\RouteHelper;
use Illuminate\Support\Facades\Mail;

// routes/web.php

use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;

RouteHelper::includeRouteFiles(__DIR__ . '/web');


Route::get('/test', function () {
    return view('layout.store');
});


Route::get('/terms-and-condition', function () {
    return view('others.term');
});

Route::get('/policy', function () {
    return view('others.policy');
});

Route::get('/contact', function () {
    return view('others.contact');
});

Route::get('/about', function () {
    return view('others.about');
});

Route::get('/faq', function () {
    return view('others.faq');
});

Route::get('/affiliate', function () {
    return view('others.affiliate');
})->name('register.affiliate');


// use App\Http\Controllers\StoreController;

// Store Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
    Route::put('/stores/{store}', [StoreController::class, 'update'])->name('stores.update');
    Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
});


Route::get('stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('stores/{slug}', [StoreController::class, 'show'])->name('stores.show');


// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/offline', function () {
    return view('offline');
});

// routes/web.php (temporary)
Route::get('/clear-caches', function() {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    return 'Caches cleared!';
});



Route::get('/mail-config', function () {
    dd(config('mail.default'), config('mail.mailers'));
});





Route::get('/test-mail', function () {

    try {
        dump('Mail test started');

        Mail::send('emails.staff-welcome', [
            'user'     => 'devkaz100@gmail.com',
            'password' => 'Kazeem#24',
            'full_name' => 'Test User',
            'staff'    => (object) [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'full_name'  => 'Test User',
            ],
        ], function ($message) {
            $message->to('devkaz100@gmail.com')
                    ->subject('Mail Test Successful 🎉');
        });

        dump('Mail send executed');

        return '✅ Mail sent (if no exception was thrown)';

    } catch (\Throwable $e) {
        dd('❌ Mail failed', $e->getMessage(), $e);
    }
});
