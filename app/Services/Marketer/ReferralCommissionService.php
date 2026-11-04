<?php

namespace App\Services\Marketer;

use App\Models\CommissionTransaction;
use App\Models\Marketer;
use App\Models\VendorSubscription;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class ReferralCommissionService
{
    // Credits the referring marketer once per subscription, returns null when there is nobody to credit
    public function recordFor(VendorSubscription $subscription): ?CommissionTransaction
    {
        $marketer = $this->marketerFor($subscription);

        if (! $marketer) {
            return null;
        }

        try {
            return DB::transaction(fn () => $this->createOnce($marketer, $subscription));
        } catch (UniqueConstraintViolationException) {
            // A concurrent webhook and callback both tried to credit, the first one already won
            return null;
        }
    }

    // Used for display before payment, so the marketer can see what they will earn
    public function previewFor(float $subscriptionAmount): float
    {
        return CommissionTransaction::calculateCommission($subscriptionAmount, $this->rate());
    }

    // The only place the rate is decided, swap this for a per-marketer or per-plan rate later
    public function rate(): float
    {
        return (float) config('marketer.commission_rate');
    }

    private function createOnce(Marketer $marketer, VendorSubscription $subscription): ?CommissionTransaction
    {
        $transaction = CommissionTransaction::firstOrCreate(
            ['subscription_id' => $subscription->id],
            $this->payload($marketer, $subscription),
        );

        if (! $transaction->wasRecentlyCreated) {
            return null;
        }

        $marketer->creditEarnings($transaction->commission_amount);

        return $transaction;
    }

    private function payload(Marketer $marketer, VendorSubscription $subscription): array
    {
        $base = $this->commissionBase($subscription);

        return [
            'marketer_id' => $marketer->id,
            'vendor_id' => $subscription->user_id,
            'amount' => $base,
            'commission_rate' => $this->rate(),
            'commission_amount' => $this->previewFor($base),
            'status' => CommissionTransaction::STATUS_PENDING,
            'payment_reference' => $subscription->payment_reference,
        ];
    }

    // VAT is tax collected for the government and not revenue, so commission is taken on the amount before VAT
    private function commissionBase(VendorSubscription $subscription): float
    {
        return (float) ($subscription->subtotal ?? $subscription->amount);
    }

    private function marketerFor(VendorSubscription $subscription): ?Marketer
    {
        $referrerId = $subscription->user?->referred_by;

        return $referrerId ? Marketer::where('user_id', $referrerId)->first() : null;
    }
}
