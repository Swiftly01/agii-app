<?php

namespace App\Services\Staff;

use App\Mail\StaffLoginDetails;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StaffCredentialService
{
    public function generatePassword(): string
    {
        return Str::password((int) config('staff.password_length'));
    }

    // Emails the login details of a new account, a mail failure is for the caller to handle
    public function sendLoginDetails(User $user, string $plainPassword): void
    {
        Mail::to($user->email)->send(new StaffLoginDetails($user, $plainPassword));
    }

    // Saves a fresh password and emails it, if the email fails the old password stays valid
    public function regenerateAndSend(User $user): void
    {
        $plainPassword = $this->generatePassword();

        DB::transaction(function () use ($user, $plainPassword) {
            $user->forceFill([
                'password' => Hash::make($plainPassword),
                // Drops "remember me" sessions that still hold the old password
                'remember_token' => Str::random(60),
            ])->save();

            Mail::to($user->email)->send(new StaffLoginDetails($user, $plainPassword, isReset: true));
        });
    }
}
