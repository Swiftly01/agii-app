<?php

namespace App\Http\Controllers;

use App\Services\DashboardRedirector;
use App\Services\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{

  public function __construct(private readonly GoogleAuthService $googleAuthService, private readonly DashboardRedirector $dashboardRedirector) {}


  public function redirect(Request $request, string $userType = 'customer'): RedirectResponse
  {

    $request->session()->put('google_auth_user_type', $this->googleAuthService->sanitizeUser($userType));

    return Socialite::driver('google')->redirect();
  }


  public function handleCallback(Request $request)
  {

    try {
      $googleUser = Socialite::driver('google')->user();
    } catch (\Throwable $e) {
      Log::warning('Google OAuth callback failed', ['error' => $e->getMessage()]);

      return redirect()->route('login')->with('error', 'Google sign-in failed. Please try again');
    }

    $userType = $request->session()->pull('google_auth_user_type', 'customer');

    $user = $this->googleAuthService->findOrCreateUser($googleUser, $userType);

    Auth::login($user, remember: true);
    $request->session()->regenerate();

    return $this->dashboardRedirector->redirect($user, 'Signed in with Google!');
  }
}
