<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\VendorSubscription;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function checkout(PaymentPlan $plan, Request $request)
    {
        $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
            'months' => 'required|integer|min:1|max:60',
        ]);

        $billingCycle = $request->billing_cycle;
        $months = $request->months;

        // Calculate total amount with VAT
        $pricing = $plan->getPriceWithVat($billingCycle, $months);

        return view('vendor.checkout', compact('plan', 'billingCycle', 'months', 'pricing'));
    }

    public function initializePayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:payment_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'months' => 'required|integer|min:1|max:60',
        ]);

        try {
            DB::beginTransaction();

            $plan = PaymentPlan::findOrFail($request->plan_id);
            $user = Auth::user();

            // Calculate amount with VAT (convert to kobo for Paystack)
            $pricing = $plan->getPriceWithVat($request->billing_cycle, $request->months);
            $amountInKobo = $pricing['total_kobo'];

            // Create pending subscription
            $subscription = VendorSubscription::create([
                'user_id' => $user->id,
                'payment_plan_id' => $plan->id,
                'billing_cycle' => $request->billing_cycle,
                'months' => $request->months,
                'amount' => $pricing['total'], // Total amount including VAT
                'subtotal' => $pricing['subtotal'], // Amount before VAT
                'vat_amount' => $pricing['vat_amount'], // VAT amount
                'vat_rate' => $pricing['vat_rate'], // VAT rate
                'status' => 'pending',
                'payment_method' => 'paystack',
            ]);

            // Prepare Paystack payment data
            $paymentData = [
                'amount' => $amountInKobo,
                'email' => $user->email,
                'reference' => $this->paystackService->generateReference(),
                'currency' => 'NGN',
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'plan_name' => $plan->name,
                    'user_id' => $user->id,
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'billing_cycle' => $request->billing_cycle,
                    'months' => $request->months,
                    'subtotal' => $pricing['subtotal'],
                    'vat_amount' => $pricing['vat_amount'],
                    'vat_rate' => $pricing['vat_rate'],
                    'total_amount' => $pricing['total'],
                    'cancel_action' => route('vendor.plans')
                ]
            ];

            // Initialize payment with Paystack
            $paymentResult = $this->paystackService->initializeTransaction($paymentData);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return redirect()->back()->with('error', $paymentResult['message']);
            }

            // Update subscription with payment reference
            $subscription->update([
                'payment_reference' => $paymentResult['reference']
            ]);

            DB::commit();

            // Redirect to Paystack payment page
            return redirect($paymentResult['authorization_url']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Paystack initialization error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment initialization failed. Please try again.');
        }
    }
    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('vendor.plans')->with('error', 'Invalid payment reference.');
        }

        try {
            // Verify transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if (!$verification['status']) {
                return redirect()->route('vendor.plans')->with('error', 'Payment verification failed.');
            }

            $transaction = $verification['data'];

            // Find subscription by payment reference
            $subscription = VendorSubscription::where('payment_reference', $reference)->first();

            if (!$subscription) {
                Log::error('Subscription not found for reference: ' . $reference);
                return redirect()->route('vendor.plans')->with('error', 'Subscription not found.');
            }

            if ($transaction['status'] === 'success') {
                DB::beginTransaction();

                // Payment successful
                $subscription->update([
                    'status' => 'active',
                    'payment_reference' => $reference,
                    'payment_method' => 'paystack',
                    'payment_details' => [
                        'gateway_response' => $transaction['gateway_response'],
                        'channel' => $transaction['channel'],
                        'ip_address' => $transaction['ip_address'],
                        'paid_at' => $transaction['paid_at'],
                        'transaction_date' => $transaction['transaction_date'],
                        'currency' => $transaction['currency'],
                        'fees' => $transaction['fees'] / 100, // Convert from kobo
                    ],
                    'starts_at' => now(),
                    'expires_at' => $this->calculateExpiryDate($subscription->billing_cycle, $subscription->months),
                ]);

                DB::commit();

                return redirect()->route('vendor.dashboard')
                    ->with('success', 'Payment successful! Your vendor account is now active.');
            } else {
                // Payment failed
                $subscription->update([
                    'status' => 'failed',
                    'payment_details' => [
                        'gateway_response' => $transaction['gateway_response'],
                        'message' => $transaction['message'],
                    ]
                ]);

                return redirect()->route('vendor.plans')
                    ->with('error', 'Payment failed: ' . ($transaction['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('vendor.plans')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    public function handleWebhook(Request $request)
    {
        // For webhook implementation (optional but recommended)
        $input = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        // Verify webhook signature
        if ($signature !== hash_hmac('sha512', $input, config('paystack.secret_key'))) {
            Log::error('Invalid Paystack webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($input, true);

        if ($event['event'] === 'charge.success') {
            $data = $event['data'];
            $reference = $data['reference'];

            $subscription = VendorSubscription::where('payment_reference', $reference)->first();

            if ($subscription && $subscription->status === 'pending') {
                DB::beginTransaction();

                $subscription->update([
                    'status' => 'active',
                    'payment_details' => $data,
                    'starts_at' => now(),
                    'expires_at' => $this->calculateExpiryDate($subscription->billing_cycle, $subscription->months),
                ]);

                DB::commit();
                Log::info('Webhook: Subscription activated for reference: ' . $reference);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function calculateExpiryDate($billingCycle, $months)
    {
        if ($billingCycle === 'yearly') {
            return now()->addYears(ceil($months / 12));
        }

        return now()->addMonths($months);
    }
}
