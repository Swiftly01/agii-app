<?php

namespace App\Http\Controllers;

use App\Services\VendorUpgradeService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BecomeSellerController extends Controller
{
    public function __construct(private readonly VendorUpgradeService $vendorUpgradeService)
    {
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->user_type !== 'customer') {
            return redirect()->route('dashboard')->with('error', 'Only customer accounts can become a seller.');
        }

        return view('dashboard.become-seller', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'customer') {
            return redirect()->route('dashboard')->with('error', 'Only customer accounts can become a seller.');
        }

        $validated = $request->validate([
            'business_type' => ['required', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_category' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
        ]);

        $this->vendorUpgradeService->upgrade($user, $validated);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Welcome! Your account is now a vendor account. You can start posting products.');
    }
}
