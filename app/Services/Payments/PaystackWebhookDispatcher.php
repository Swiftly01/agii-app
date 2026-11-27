<?php

namespace App\Services\Payments;

use App\Contracts\PaystackChargeHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

// Single entry for Paystack events, every tagged handler gets the chance to claim the reference
final class PaystackWebhookDispatcher
{
    // @param iterable<PaystackChargeHandler> $handlers
    public function __construct(private readonly iterable $handlers) {}

    public function dispatch(array $event): void
    {
        $reference = $event['data']['reference'] ?? null;

        if (($event['event'] ?? null) !== 'charge.success' || ! $reference) {
            return;
        }

        foreach ($this->handlers as $handler) {
            try {
                $handler->activateFromWebhook($reference, $event['data']);
            } catch (Throwable $e) {
                // One failing handler must not stop the others or make Paystack retry forever
                Log::error('Paystack webhook handler failed', ['handler' => $handler::class, 'reference' => $reference, 'error' => $e->getMessage()]);
            }
        }
    }
}
