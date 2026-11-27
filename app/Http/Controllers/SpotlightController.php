<?php

namespace App\Http\Controllers;

use App\Http\Requests\Spotlight\SpotlightProductRequest;
use App\Services\Spotlight\SpotlightPaymentService;
use App\Services\Spotlight\SpotlightService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class SpotlightController extends Controller
{
    public function __construct(
        private readonly SpotlightService $spotlight,
        private readonly SpotlightPaymentService $payments,
    ) {}

    public function show(Request $request): View
    {
        $vendor = $request->user();

        return view('spotlight.manage', [
            'spotlight' => $vendor->heroSpotlight()->with('product:id,title')->first(),
            'products' => $this->spotlight->eligibleProducts($vendor),
            'monthlyPrice' => config('hero_spotlight.monthly_price'),
            'purchasesOpen' => config('hero_spotlight.enabled'),
        ]);
    }

    // Sends the vendor to Paystack to pay for one month
    public function checkout(SpotlightProductRequest $request): RedirectResponse
    {
        try {
            $gateway = $this->payments->initiateCheckout($request->user(), $request->integer('product_id'));

            return redirect($gateway['authorization_url']);
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        } catch (Throwable $e) {
            Log::error('Spotlight checkout error: '.$e->getMessage());

            return back()->with('error', 'Could not start checkout. Please try again.');
        }
    }

    // Paystack sends the vendor back here once the charge is done
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference');

        if (! $reference) {
            return redirect()->route('spotlight.show')->with('error', 'Invalid payment reference.');
        }

        try {
            $spotlight = $this->payments->verifyAndActivate($request->user(), $reference);

            return redirect()->route('spotlight.show')
                ->with('success', 'Your product is live in the homepage hero until '.$spotlight->expires_at->format('M j, Y').'.');
        } catch (Throwable $e) {
            Log::error('Spotlight callback error: '.$e->getMessage());

            return redirect()->route('spotlight.show')
                ->with('error', 'Payment verification failed. Please contact support if you were charged.');
        }
    }

    // Swaps the showcased listing while the spotlight is still running
    public function changeProduct(SpotlightProductRequest $request): RedirectResponse
    {
        try {
            $this->spotlight->changeProduct($request->user(), $request->integer('product_id'));

            return back()->with('success', 'Your hero spotlight now shows the new product.');
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }
    }
}
