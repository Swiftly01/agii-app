<?php
// app/Http/Controllers/PaymentPlanController.php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentPlanController extends Controller
{
    public function showPlans()
    {
        $regularPlans = PaymentPlan::active()->byType('regular')->orderBy('sort_order')->get();
        $storesPlans = PaymentPlan::active()->byType('stores')->orderBy('sort_order')->get();

        return view('vendor.payment-plans', compact('regularPlans', 'storesPlans'));
    }

    public function selectPlan(PaymentPlan $plan, Request $request)
    {
        if (Auth::user()->user_type !== 'vendor') {
            return redirect()->route('home')->with('error', 'Only vendors can select payment plans.');
        }

        $billingCycle = $request->get('billing_cycle', 'monthly');
        $months = 1; // Default duration

        // Calculate pricing
        $basePrice = $billingCycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

        // For yearly billing, calculate based on years
        if ($billingCycle === 'yearly') {
            $subtotal = $basePrice * ceil($months / 12);
        } else {
            $subtotal = $basePrice * $months;
        }

        $vatRate = 7.5; // Nigerian VAT rate
        $vatAmount = ($subtotal * $vatRate) / 100;
        $totalAmount = $subtotal + $vatAmount;

        $pricing = [
            'subtotal' => $subtotal,
            'vat_rate' => $vatRate,
            'vat_amount' => $vatAmount,
            'total' => $totalAmount
        ];

        return view('vendor.checkout', compact('plan', 'billingCycle', 'months', 'pricing'));
    }
}
