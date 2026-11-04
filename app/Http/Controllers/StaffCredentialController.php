<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Services\Staff\StaffCredentialService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class StaffCredentialController extends Controller
{
    public function __construct(
        private readonly StaffCredentialService $credentials,
    ) {}

    public function regenerate(int $id): RedirectResponse
    {
        $staff = StaffProfile::with('user')->findOrFail($id);

        try {
            $this->credentials->regenerateAndSend($staff->user);
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'The email could not be sent, so the password was not changed. Please try again.');
        }

        return back()->with('success', 'A new password was generated and emailed to ' . $staff->user->email . '.');
    }
}
