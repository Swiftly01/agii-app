<?php

namespace App\Services;

use App\Enums\VendorStatus;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class VendorUpgradeService
{

    public function upgrade(User $user, array $data): User
    {
        if ($user->user_type !== 'customer') {
            throw ValidationException::withMessages([
                'user_type' => 'Only customer accounts can become a seller.',
            ]);
        }

        $user->forceFill([
            'user_type' => 'vendor',
            // Upgrading grants selling rights immediately, no admin review.
            'vendor_status' => VendorStatus::Approved->value,
            'vendor_approved_at' => now(),
            'vendor_rejection_reason' => null,
            'business_name' => $data['business_name'] ?? null,
            'business_type' => $data['business_type'],
            'business_category' => $data['business_category'] ?? null,
            'whatsapp_number' => $data['whatsapp_number'] ?? $user->phone,
            'facebook_url' => $data['facebook_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'twitter_url' => $data['twitter_url'] ?? null,
        ])->save();

        return $user->fresh();
    }
}
