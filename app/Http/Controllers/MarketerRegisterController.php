<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarketerRegisterRequest;
use App\Services\Marketer\MarketerRegistrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MarketerRegisterController extends Controller
{
    public function __construct(private readonly MarketerRegistrationService $registration) {}

    public function create(): View
    {
        return view('auth.marketer-register');
    }

    // After sign up the home route sends them to verify their email, then to their dashboard
    public function store(MarketerRegisterRequest $request): RedirectResponse
    {
        $user = $this->registration->register($request->validated());

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please verify your email address to get started.');
    }
}
