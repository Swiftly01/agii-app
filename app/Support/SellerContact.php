<?php

namespace App\Support;

use App\Models\User;

final class SellerContact
{
    private function __construct(
        private readonly ?string $phone,
        private readonly ?string $whatsapp,
    ) {}

    // Builds contact links from the seller's stored numbers, WhatsApp falls back to the phone
    public static function for(?User $seller): self
    {
        $code = config('phone_verification.default_country_code');
        $normalize = fn (?string $number) => filled($number) ? PhoneNumber::normalize($number, $code) : null;

        $phone = $normalize($seller?->phone);

        return new self($phone, $normalize($seller?->whatsapp_number) ?? $phone);
    }

    public function isAvailable(): bool
    {
        return $this->phone !== null || $this->whatsapp !== null;
    }

    public function phoneUrl(): ?string
    {
        return $this->phone ? 'tel:'.$this->phone : null;
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        if ($this->whatsapp === null) {
            return null;
        }

        $url = 'https://wa.me/'.ltrim($this->whatsapp, '+');

        return $message ? $url.'?text='.rawurlencode($message) : $url;
    }
}
