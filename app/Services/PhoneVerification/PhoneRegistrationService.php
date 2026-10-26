<?php

namespace App\Services\PhoneVerification;

use App\Enums\VendorStatus;
use App\Models\User;
use App\Services\ReferralCodeGenerator;

final class PhoneRegistrationService
{
    public function __construct(private readonly ReferralCodeGenerator $referralCodes) {}

    // Expects data already validated by PhoneRegisterRequest with phone in E.164 format
    public function register(array $data): User
    {
        $userType = $data['user_type'];

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'user_type' => $userType,
            'vendor_status' => $userType === 'vendor' ? VendorStatus::Pending->value : null,
            'referral_code' => $this->referralCodes->generate($data['first_name']),
            'referred_by' => $this->referrerId($data['referral_code'] ?? null),
            'terms_accepted' => true,
        ]);
    }

    private function referrerId(?string $referralCode): ?int
    {
        return filled($referralCode)
            ? User::where('referral_code', $referralCode)->value('id')
            : null;
    }
}
