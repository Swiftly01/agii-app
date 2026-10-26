<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class ReferralCodeGenerator
{
    public function generate(string $firstName): string
    {
        $prefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $firstName) ?: 'USER', 0, 3));

        do {
            $code = $prefix.random_int(1000, 9999);
        } while (User::where('referral_code', $code)->exists());

        Log::info($code);

        return $code;
    }
}
