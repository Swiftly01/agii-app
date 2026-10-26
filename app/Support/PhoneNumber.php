<?php

namespace App\Support;

final class PhoneNumber
{
    private const E164_PATTERN = '/^\+[1-9]\d{7,14}$/';

    // Converts user input such as 0803 123 4567 or +234 (0)803... into E.164, null when unusable
    public static function normalize(string $input, string $defaultCountryCode): ?string
    {
        $cleaned = preg_replace('/[^\d+]/', '', $input) ?? '';

        if ($cleaned === '') {
            return null;
        }

        if (str_starts_with($cleaned, '00')) {
            $cleaned = '+'.substr($cleaned, 2);
        }

        $e164 = match (true) {
            str_starts_with($cleaned, '+') => '+'.preg_replace('/\D/', '', $cleaned),
            str_starts_with($cleaned, '0') => '+'.$defaultCountryCode.substr($cleaned, 1),
            str_starts_with($cleaned, $defaultCountryCode) => '+'.$cleaned,
            default => '+'.$defaultCountryCode.$cleaned,
        };

        // Drops the trunk zero people keep after the country code, e.g. +2340803...
        $e164 = preg_replace('/^\+'.preg_quote($defaultCountryCode, '/').'0/', '+'.$defaultCountryCode, $e164);

        return preg_match(self::E164_PATTERN, $e164) === 1 ? $e164 : null;
    }

    public static function mask(string $e164): string
    {
        $hidden = max(strlen($e164) - 7, 0);

        return substr($e164, 0, 4).str_repeat('*', $hidden).substr($e164, -3);
    }
}
