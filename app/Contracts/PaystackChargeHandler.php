<?php

namespace App\Contracts;

// Anything that settles its own payments when Paystack reports a successful charge
interface PaystackChargeHandler
{
    // Must ignore references it does not own and be safe to run twice for the same reference
    public function activateFromWebhook(string $reference, array $transaction): void;
}
