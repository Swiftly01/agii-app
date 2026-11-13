<?php

namespace App\Services\Marketer;

use App\Enums\MarketerStatus;
use App\Models\Marketer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class MarketerRegistrationService
{
    public function __construct(private readonly MarketerReferralCodeGenerator $referralCodes) {}

    // Expects data already validated by MarketerRegisterRequest, creates the login and its pending marketer profile together
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'user_type' => 'marketer',
                'state' => $data['state'],
                'local_government' => $data['local_government'],
                'address' => $data['address'],
                'terms_accepted' => true,
            ]);

            Marketer::create([
                'user_id' => $user->id,
                'referral_code' => $this->referralCodes->generate($data['first_name']),
                'is_active' => false,
                'status' => MarketerStatus::Pending,
                'application_note' => $data['application_note'] ?? null,
            ]);

            return $user;
        });
    }
}
