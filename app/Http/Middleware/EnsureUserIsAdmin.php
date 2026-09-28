<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * The /admin route group previously only required `auth` — any logged-in
 * customer or vendor could hit admin endpoints, including the vendor/product
 * approve-reject actions this feature adds. Gating approval behind "must be
 * logged in" while leaving it open to any account defeats the point of an
 * approval workflow, so this closes that gap for the whole /admin group.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'Admin access required.');
        }

        return redirect()->route('admin.login')
            ->with('error', 'Please sign in with an admin account.');
    }
}
