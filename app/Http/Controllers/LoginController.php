<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardRedirector;
use App\Support\PhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(private readonly DashboardRedirector $dashboardRedirector) {}
    /**
     * Display login page
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Remove the dd() once it's working
        // dd($request->all());

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $this->credentialsFor(trim($request->email), $request->password);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return $this->dashboardRedirector->redirect(Auth::user());
            /*
        $user = Auth::user();
    
        // ✅ Staff check FIRST
        if ($user->staffProfile) {
            return redirect()->route('staff.dashboard')
                ->with('success', 'Welcome to the staff dashboard!');
        }
    
        // 🔀 User type routing
        switch ($user->user_type) {
            case 'vendor':
                return redirect()->route('vendor.dashboard')
                    ->with('success', 'Welcome to your vendor dashboard!');
    
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome to the admin dashboard!');
    
            case 'marketer':
                return redirect()->route('marketer.dashboard')
                    ->with('success', 'Welcome to the marketer dashboard!');
    
            case 'affiliate':
                return redirect()->route('affiliate.dashboard')
                    ->with('success', 'Welcome to the affiliate dashboard!');
    
            case 'customer':
                return redirect('/');
    
            default:
                return redirect('/')
                    ->with('error', 'User type not recognized.');
        }

        */
        }


        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // The email field accepts either an email address or a phone number
    private function credentialsFor(string $identifier, string $password): array
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $identifier, 'password' => $password];
        }

        $phone = PhoneNumber::normalize($identifier, config('phone_verification.default_country_code'));

        return ['phone' => $phone ?? $identifier, 'password' => $password];
    }

    /**
     * Handle registration (if you need it)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
