<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffLoginDetails extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword,
        public bool $isReset = false,
    ) {}

    public function build()
    {
        // Subject changes between a new account and a password reset
        return $this->subject($this->isReset ? 'Your password has been reset' : 'Your staff account login details')
            ->view('emails.staff-login-details');
    }
}