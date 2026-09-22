<?php

namespace App\Services;

use App\Models\BoostSubscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BoostPaymentService
{
    public function __construct(
        private readonly PaystackService $paystackService,
        private readonly ProductBoostService $productBoostService,
    ) {
    }

    public function initiateCheckout(User $vendor): array
    {
        $amount = (float) config('boost.monthly_price');

        return DB::transaction(function () use ($vendor, $amount) {
            $subscription = BoostSubscription::create([
                'user_id' => $vendor->id,
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => 'paystack',
            ]);

            $payment = $this->paystackService->initializeTransaction([
                'amount' => (int) round($amount * 100), // kobo
                'email' => $vendor->email,
                'reference' => $this->paystackService->generateReference(),
                'currency' => 'NGN',
                'callback_url' => route('boost.callback'),
                'metadata' => [
                    'boost_subscription_id' => $subscription->id,
                    'user_id' => $vendor->id,
                ],
            ]);

            if (! $payment['success']) {
                $subscription->update(['status' => 'failed']);
                throw new RuntimeException($payment['message'] ?? 'Payment initialization failed.');
            }

            $subscription->update(['payment_reference' => $payment['reference']]);

            return $payment;
        });
    }

    
    public function verifyAndActivate(string $reference): BoostSubscription
    {
        $subscription = BoostSubscription::where('payment_reference', $reference)->firstOrFail();
        $verification = $this->paystackService->verifyTransaction($reference);

        if (empty($verification['status']) || ($verification['data']['status'] ?? null) !== 'success') {
            $subscription->update([
                'status' => 'failed',
                'payment_details' => $verification['data'] ?? null,
            ]);

            throw new RuntimeException('Payment verification failed.');
        }

        $this->activate($subscription, $verification['data']);

        return $subscription->fresh();
    }

    
    public function activateFromWebhook(string $reference, array $transactionData): void
    {
        $subscription = BoostSubscription::where('payment_reference', $reference)
            ->where('status', 'pending')
            ->first();

        if (! $subscription) {
            return;
        }

        $this->activate($subscription, $transactionData);

        Log::info('Boost subscription activated via webhook', ['reference' => $reference]);
    }

    private function activate(BoostSubscription $subscription, array $transaction): void
    {
        $expiresAt = now()->addMonth();

        DB::transaction(function () use ($subscription, $transaction, $expiresAt) {
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'payment_details' => [
                    'gateway_response' => $transaction['gateway_response'] ?? null,
                    'channel' => $transaction['channel'] ?? null,
                    'paid_at' => $transaction['paid_at'] ?? null,
                    'currency' => $transaction['currency'] ?? null,
                ],
            ]);

            $this->productBoostService->syncBoostedProductsExpiry($subscription->user, $expiresAt);
        });
    }
}
