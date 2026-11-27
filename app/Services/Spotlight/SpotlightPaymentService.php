<?php

namespace App\Services\Spotlight;

use App\Contracts\PaystackChargeHandler;
use App\Enums\SpotlightPaymentStatus;
use App\Models\HeroSpotlight;
use App\Models\HeroSpotlightPayment;
use App\Models\User;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

// Paystack checkout and verification for hero spotlight months
final class SpotlightPaymentService implements PaystackChargeHandler
{
    public function __construct(
        private readonly PaystackService $paystack,
        private readonly SpotlightService $spotlight,
    ) {}

    // Records a pending payment and returns the Paystack payload holding the redirect url
    public function initiateCheckout(User $vendor, int $productId): array
    {
        throw_unless(config('hero_spotlight.enabled'), ValidationException::withMessages([
            'spotlight' => 'Hero spotlight is not open for new purchases right now.',
        ]));

        $product = $this->spotlight->eligibleProduct($vendor, $productId);

        $payment = HeroSpotlightPayment::create([
            'user_id' => $vendor->id,
            'product_id' => $product->id,
            'amount' => (float) config('hero_spotlight.monthly_price'),
            'status' => SpotlightPaymentStatus::Pending,
            'reference' => $this->paystack->generateReference(),
        ]);

        $gateway = $this->paystack->initializeTransaction([
            'amount' => $payment->amountInKobo(),
            'email' => $vendor->email,
            'reference' => $payment->reference,
            'currency' => 'NGN',
            'callback_url' => route('spotlight.callback'),
            'metadata' => [
                'hero_spotlight_payment_id' => $payment->id,
                'user_id' => $vendor->id,
            ],
        ]);

        if (! $gateway['success']) {
            $payment->update(['status' => SpotlightPaymentStatus::Failed]);

            throw new RuntimeException($gateway['message'] ?? 'Payment initialization failed.');
        }

        return $gateway;
    }

    // Confirms the charge with Paystack and extends the vendor's slot, safe to call twice for one payment
    public function verifyAndActivate(User $vendor, string $reference): HeroSpotlight
    {
        $payment = HeroSpotlightPayment::where('user_id', $vendor->id)
            ->where('reference', $reference)
            ->firstOrFail();

        if ($payment->status === SpotlightPaymentStatus::Paid) {
            return $vendor->heroSpotlight;
        }

        return $this->settle($payment, $this->paystack->verifyTransaction($reference)['data'] ?? []);
    }

    // Paystack webhook path, covers vendors who paid but never made it back to the callback page
    public function activateFromWebhook(string $reference, array $transaction): void
    {
        $payment = HeroSpotlightPayment::where('reference', $reference)->first();

        if ($payment && $payment->status !== SpotlightPaymentStatus::Paid) {
            $this->settle($payment, $transaction);
        }
    }

    // Marks the payment paid and grants the month, callback and webhook both end up here
    private function settle(HeroSpotlightPayment $payment, array $transaction): HeroSpotlight
    {
        $this->assertPaid($payment, $transaction);

        return DB::transaction(function () use ($payment, $transaction) {
            // Re-read under lock so a callback and a webhook racing cannot grant the same month twice
            $locked = HeroSpotlightPayment::with('user')->whereKey($payment->id)->lockForUpdate()->first();

            if ($locked->status === SpotlightPaymentStatus::Paid) {
                return $locked->user->heroSpotlight;
            }

            $locked->update([
                'status' => SpotlightPaymentStatus::Paid,
                'paid_at' => now(),
                'payment_details' => [
                    'gateway_response' => $transaction['gateway_response'] ?? null,
                    'channel' => $transaction['channel'] ?? null,
                    'paid_at' => $transaction['paid_at'] ?? null,
                    'currency' => $transaction['currency'] ?? null,
                ],
            ]);

            return $this->spotlight->grantMonth($locked->user, $locked->product_id);
        });
    }

    // Only a successful charge for the exact amount we asked for counts
    private function assertPaid(HeroSpotlightPayment $payment, array $transaction): void
    {
        $status = $transaction['status'] ?? null;

        if ($status === 'failed') {
            $payment->update(['status' => SpotlightPaymentStatus::Failed]);
        }

        throw_unless(
            $status === 'success' && (int) ($transaction['amount'] ?? 0) === $payment->amountInKobo(),
            RuntimeException::class,
            'Payment verification failed.'
        );
    }
}
