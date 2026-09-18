<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DashboardRedirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __construct(private readonly DashboardRedirector $dashboardRedirector) {}
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->create($request->all());

        // Fire the Registered event (this will trigger email verification)
        event(new Registered($user));

        // Auto-login the user after registration
        Auth::login($user);

        // Redirect to email verification notice page instead of dashboard
        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please verify your email address.');
    }

    protected function validator(array $data)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required'],
            'state' => ['required', 'string', 'max:255'],
            'local_government' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'agree_terms' => ['required', 'accepted'],
        ];

        // Vendor-specific validation
        if ($data['user_type'] === 'vendor') {
            $rules['business_type'] = ['required', 'string'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $code = $data['referral_code'] ?? null;
        $referredBy = $this->getReferredBy($data['referral_code'] ?? $code);

        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'state' => $data['state'],
            'local_government' => $data['local_government'],
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            // 'referral_code' => $data['referral_code'] ?? null,
            'referred_by' => $referredBy,
            'newsletter_subscribed' => isset($data['newsletter']),
            'terms_accepted' => true,
            'email_verified_at' => null, // Explicitly set to null
        ];

        // Add vendor-specific fields
        if ($data['user_type'] === 'vendor') {
            $userData = array_merge($userData, [
                'business_name' => $data['business_name'] ?? null,
                'business_type' => $data['business_type'] ?? null,
                'business_category' => $data['business_category'] ?? null,
                'facebook_url' => $data['facebook_link'] ?? null,
                'instagram_url' => $data['instagram_link'] ?? null,
                'twitter_url' => $data['twitter_link'] ?? null,
                'whatsapp_number' => $data['whatsapp_link'] ?? null,
            ]);
        } else {
            // Add customer-specific fields
            $userData['location'] = $data['location'] ?? null;
            $userData['interests'] = !empty($data['interests']) ? json_encode($data['interests']) : null;
        }

        return User::create($userData);
    }

    protected function getReferredBy($referralCode)
    {
        if (!$referralCode) {
            return null;
        }

        $referrer = User::where('referral_code', $referralCode)->first();
        return $referrer ? $referrer->id : null;
    }

    /**
     * This method is now used after email verification
     */
    /*
    protected function redirectToDashboard(User $user)
    {
        switch ($user->user_type) {
            case 'vendor':
                return redirect()->route('vendor.dashboard')
                    ->with('success', 'Welcome to your vendor dashboard!');

            case 'customer':
                return redirect('/');
                
           case 'affiliate':
                    return redirect()->route('affiliate.dashboard')
                        ->with('success', 'Welcome to the affiliate dashboard!');
             
            default:
                return redirect('/')->with('error', 'User type not recognized.');
        }
    }
        */



    public function registerAffiliate(Request $request)
    {
        // 1. Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|unique:users,phone',
        ]);

        // 2. Generate referral code from name + random numbers
        $namePart = strtoupper(substr(preg_replace('/\s+/', '', $request->name), 0, 3));
        $randomNumbers = rand(1000, 9999);
        $referralCode = $namePart . $randomNumbers;

        // 3. Create affiliate user
        $user = User::create([
            'first_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'user_type' => 'affiliate',
            'referral_code' => $referralCode,
        ]);

        // 4. Send email verification
        event(new Registered($user));


        return redirect()->route('verification.notice')
            ->with('success', 'Affiliate registered successfully! Please verify your email before logging in.');
    }
}
