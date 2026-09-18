<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorSubscription;
use Illuminate\Support\Facades\Auth;

class VendorSubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        return view('vendor.subscription.index', compact('subscription'));
    }

    public function create()
    {
        // Show subscription plans
        $plans = [
            ['id' => 1, 'name' => 'Basic', 'price' => 5000, 'duration' => 30],
            ['id' => 2, 'name' => 'Professional', 'price' => 10000, 'duration' => 30],
            ['id' => 3, 'name' => 'Enterprise', 'price' => 20000, 'duration' => 30],
        ];

        return view('vendor.subscription.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:payment_plans,id',
            'payment_method' => 'required|string',
        ]);

        // Process subscription logic here
        // This would typically integrate with a payment gateway

        return redirect()->route('vendor.subscription')
            ->with('success', 'Subscription activated successfully!');
    }

    public function cancel()
    {
        $user = Auth::user();

        // Cancel subscription logic
        $user->activeSubscription()->update(['status' => 'cancelled']);

        return redirect()->route('vendor.subscription')
            ->with('success', 'Subscription cancelled successfully!');
    }
}
