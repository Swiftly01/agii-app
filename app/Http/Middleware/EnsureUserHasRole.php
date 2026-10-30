<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: ->middleware('role:staff') or ->middleware('role:staff,admin')
 *
 * Gates a route group to specific user_type values, so a user whose role is
 * changed away from "staff" can no longer open the staff dashboard.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if ($user && in_array($user->user_type, $roles, true)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'You do not have access to this area.');
        }

        return redirect('/')->with('error', 'You do not have access to that dashboard.');
    }
}
