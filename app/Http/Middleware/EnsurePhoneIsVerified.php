<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    // Routes a phone-only account can still reach before it is verified
    private const EXEMPT_ROUTES = ['phone.verification.*', 'logout'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->requiresPhoneVerification() || $request->routeIs(self::EXEMPT_ROUTES)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'Your phone number is not verified.');
        }

        return redirect()->route('phone.verification.notice');
    }
}
