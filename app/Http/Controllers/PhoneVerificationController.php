<?php

namespace App\Http\Controllers;

use App\Exceptions\PhoneVerificationException;
use App\Http\Requests\VerifyPhoneRequest;
use App\Services\DashboardRedirector;
use App\Services\PhoneVerification\PhoneVerificationService;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PhoneVerificationController extends Controller
{
    public function __construct(
        private readonly PhoneVerificationService $verification,
        private readonly DashboardRedirector $dashboardRedirector,
    ) {}

    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->requiresPhoneVerification()) {
            return $this->dashboardRedirector->redirect($user);
        }

        return view('auth.phone-verify', [
            'maskedPhone' => PhoneNumber::mask($user->phone),
            'resendIn' => $this->verification->resendAvailableIn($user),
            'codeLength' => config('phone_verification.code_length'),
        ]);
    }

    public function verify(VerifyPhoneRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->requiresPhoneVerification()) {
            return $this->dashboardRedirector->redirect($user);
        }

        try {
            $this->verification->verifyCode($user, $request->validated('code'));
        } catch (PhoneVerificationException $e) {
            return back()->withErrors(['code' => $e->getMessage()]);
        }

        return $this->dashboardRedirector->redirect($user, 'Phone number verified successfully!');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->requiresPhoneVerification()) {
            return $this->dashboardRedirector->redirect($user);
        }

        try {
            $this->verification->sendCode($user);
        } catch (PhoneVerificationException $e) {
            return back()->withErrors(['code' => $e->getMessage()]);
        }

        return back()->with('status', 'code-sent');
    }
}
