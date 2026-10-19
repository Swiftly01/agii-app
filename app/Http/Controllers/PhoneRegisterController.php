<?php

namespace App\Http\Controllers;

use App\Exceptions\PhoneVerificationException;
use App\Http\Requests\PhoneRegisterRequest;
use App\Services\PhoneVerification\PhoneRegistrationService;
use App\Services\PhoneVerification\PhoneVerificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PhoneRegisterController extends Controller
{
    public function __construct(
        private readonly PhoneRegistrationService $registration,
        private readonly PhoneVerificationService $verification,
    ) {}

    public function create(): View
    {
        return view('auth.phone-register');
    }

    public function store(PhoneRegisterRequest $request): RedirectResponse
    {
        $user = $this->registration->register($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        try {
            $this->verification->sendCode($user);
        } catch (PhoneVerificationException $e) {
            return redirect()->route('phone.verification.notice')->withErrors(['code' => $e->getMessage()]);
        }

        return redirect()->route('phone.verification.notice')
            ->with('success', 'Account created. Enter the code we sent to your phone.');
    }
}
