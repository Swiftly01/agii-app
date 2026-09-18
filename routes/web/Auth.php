<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('logining', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/registration-success', [RegisterController::class, 'success'])->name('registration.success');


Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::prefix('auth/google')->name('auth.google.')->group(function() {
    Route::get('/redirect/{userType?}', [GoogleAuthController::class, 'redirect'])->name('redirect');
    Route::get('/callback', [GoogleAuthController::class, 'handleCallback'])->name('callback');
});


// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// // Protected Dashboard Routes (require verified email)
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/vendor/dashboard', function () {
//         return view('vendor.dashboard');
//     })->name('vendor.dashboard');

//     Route::get('/customer/dashboard', function () {
//         return view('customer.dashboard');
//     })->name('customer.dashboard');
// });


Route::get('/home', function () {
    $user = Auth::user(); // or $user = auth()->user();

    if ($user && $user->hasVerifiedEmail()) {
        return app(EmailVerificationController::class)
            ->redirectToDashboard($user);
    }

    return redirect()->route('verification.notice');
})->middleware('auth')->name('home');