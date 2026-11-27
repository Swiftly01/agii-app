<?php

namespace App\Services\Marketer;

use App\Enums\MarketerStatus;
use App\Models\Marketer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class MarketerRegistrationService
{
    public function __construct(
        private readonly MarketerReferralCodeGenerator $referralCodes,
        private readonly MarketerCertificateStorage $certificates,
    ) {}

    // Expects data already validated by MarketerRegisterRequest, the marketer is approved straight away
    public function register(array $data): User
    {
        // Stored first so the path can be saved with the profile, removed again if the sign up fails
        $certificatePath = isset($data['certificate']) ? $this->certificates->store($data['certificate']) : null;

        try {
            return $this->createAccount($data, $certificatePath);
        } catch (Throwable $e) {
            $this->certificates->delete($certificatePath);

            throw $e;
        }
    }

    // Creates the login and its marketer profile together in one transaction
    private function createAccount(array $data, ?string $certificatePath): User
    {
        return DB::transaction(function () use ($data, $certificatePath) {
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
                'is_active' => true,
                'status' => MarketerStatus::Approved,
                'approved_at' => now(),
                'application_note' => $data['application_note'] ?? null,
                'years_of_experience' => $data['years_of_experience'],
                'education_level' => $data['education_level'],
                'certificate_path' => $certificatePath,
            ]);

            return $user;
        });
    }
}
