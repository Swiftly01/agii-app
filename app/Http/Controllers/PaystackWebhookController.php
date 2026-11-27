<?php

namespace App\Http\Controllers;

use App\Services\Payments\PaystackWebhookDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function __invoke(Request $request, PaystackWebhookDispatcher $dispatcher): JsonResponse
    {
        $payload = $request->getContent();
        $expected = hash_hmac('sha512', $payload, (string) config('paystack.secret_key'));

        if (! hash_equals($expected, (string) $request->header('x-paystack-signature'))) {
            Log::warning('Paystack webhook rejected: invalid signature');

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $dispatcher->dispatch(json_decode($payload, true) ?? []);

        return response()->json(['status' => 'success']);
    }
}
