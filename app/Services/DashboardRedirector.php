<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class DashboardRedirector
{

  public function redirect(User $user, ?string $successMessage = null): RedirectResponse
  {   
      
    if ($user->staffProfile) {
      return redirect()->route('staff.dashboard')->with('success', $successMessage ?? 'Welcome to the staff dashboard!');
    }

    return match ($user->user_type) {
      'vendor' => redirect()->route('vendor.dashboard')->with('success', $successMessage ?? 'Welcome to your vendor dashboard!'),

      'admin' => redirect()
        ->route('admin.dashboard')
        ->with('success', $successMessage ?? 'Welcome to the admin dashboard!'),

      'marketer' => redirect()
        ->route('marketer.dashboard')
        ->with('success', $successMessage ?? 'Welcome to the marketer dashboard!'),

      'affiliate' => redirect()
        ->route('affiliate.dashboard')
        ->with('success', $successMessage ?? 'Welcome to the affiliate dashboard!'),

      'customer' => redirect('/')->with('success', $successMessage),

      default => redirect('/')->with('error', 'User type not recognized')
    };
  }
}
