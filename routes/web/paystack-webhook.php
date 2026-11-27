<?php

use App\Http\Controllers\PaystackWebhookController;
use Illuminate\Support\Facades\Route;

// The one URL to set as the webhook in the Paystack dashboard, no auth and no CSRF because Paystack calls it
Route::post('/paystack/webhook', PaystackWebhookController::class)->name('paystack.webhook');
