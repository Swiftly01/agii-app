<?php

namespace App\Http\Controllers;

use App\Services\BoostPaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class BoostSubscriptionController extends Controller
{
    public function __construct(private readonly BoostPaymentService $boostPaymentService)
    {
    }

    public function show()
    {
        $vendor = Auth::user();
        $subscription = $vendor->activeBoostSubscription();
        $monthlyPrice = config('boost.monthly_price');

        return view('boost.plans', compact('subscription', 'monthlyPrice'));
    }

    public function checkout(): RedirectResponse
    {
        try {
            $payment = $this->boostPaymentService->initiateCheckout(Auth::user());

            return redirect($payment['authorization_url']);
        } catch (Throwable $e) {
            Log::error('Boost checkout error: ' . $e->getMessage());

            return back()->with('error', 'Could not start checkout. Please try again.');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference');

        if (! $reference) {
            return redirect()->route('boost.plans')->with('error', 'Invalid payment reference.');
        }

        try {
            $this->boostPaymentService->verifyAndActivate($reference);

            return redirect()->route('vendor.showadvert')
                ->with('success', 'Boost subscription active! You can now boost your products.');
        } catch (Throwable $e) {
            Log::error('Boost callback error: ' . $e->getMessage());

            return redirect()->route('boost.plans')->with('error', 'Payment verification failed. Please contact support if you were charged.');
        }
    }

    public function webhook(Request $request)
    {
        $input = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        if ($signature !== hash_hmac('sha512', $input, config('paystack.secret_key'))) {
            Log::error('Invalid Paystack webhook signature (boost)');

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($input, true);

        if (($event['event'] ?? null) === 'charge.success') {
            $this->boostPaymentService->activateFromWebhook(
                $event['data']['reference'],
                $event['data']
            );
        }

        return response()->json(['status' => 'success']);
    }
}
