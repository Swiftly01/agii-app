<?php
// app/Http/Controllers/Auth/EmailVerificationController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DashboardRedirector;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly DashboardRedirector $dashboardRedirector) {}
    /**
     * Send verification email
     */
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectToDashboard($request->user());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Verify email
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return $this->redirectToDashboard($user);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirectToDashboard($user)->with('verified', true);
    }

    /**
     * Show verification notice
     */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? $this->redirectToDashboard($request->user())
            : view('auth.verify-email');
    }

    /**
     * Redirect to appropriate dashboard based on user type
     */
    public function redirectToDashboard(User $user)
    {

        return $this->dashboardRedirector->redirect($user, 'Email verified successfully!');
        // switch ($user->user_type) {
        //     case 'vendor':
        //         return redirect()->route('vendor.dashboard')
        //             ->with('success', 'Email verified successfully! Welcome to your vendor dashboard!');

        //     case 'customer':
        //         return redirect()->route('customer.dashboard')
        //             ->with('success', 'Email verified successfully! Welcome to your customer dashboard!');

        //     default:
        //         return redirect('/')->with('error', 'User type not recognized.');
        // }
    }

    protected function redirectAfterVerification(User $user)
    {
        // if ($user->user_type === 'vendor' && !$user->hasActiveSubscription()) {
        //     // Redirect vendors to payment plans after verification
        //     return redirect()->route('vendor.plans')
        //         ->with('success', 'Email verified successfully! Please choose a payment plan to activate your vendor account.');
        // }

        return $this->redirectToDashboard($user);
    }
}
