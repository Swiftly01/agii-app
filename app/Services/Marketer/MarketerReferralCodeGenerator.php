<?php

namespace App\Services\Marketer;

use App\Models\Marketer;
use Illuminate\Support\Str;

// Builds a unique code like BRY-1971 from the marketer's first name
final class MarketerReferralCodeGenerator
{
    public function generate(string $firstName): string
    {
        $prefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $firstName) ?: 'MKT', 0, 3));

        do {
            $code = $prefix.'-'.random_int(1000, 9999);
        } while (Marketer::where('referral_code', $code)->exists());

        return $code;
    }
}
