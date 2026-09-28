<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate for anything that lets a vendor put a product in front of buyers
 * (creating a listing today; could later also gate boosting, store
 * creation, etc.). Non-vendor users (customers, admins, marketers) are
 * left alone here — pair with the `vendor` middleware where "must be a
 * vendor at all" also needs enforcing.
 */
class EnsureVendorIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || $user->user_type !== 'vendor') {
            return $next($request);
        }

        if ($user->isVendorApproved()) {
            return $next($request);
        }

        $message = $user->isVendorRejected()
            ? 'Your vendor application was not approved. '.($user->vendor_rejection_reason ?: '')
            : 'Your vendor account is still awaiting admin approval. You can list products once approved.';

        return redirect()
            ->route('vendor.dashboard')
            ->with('error', trim($message));
    }
}
