<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Keeps unapproved marketers on their dashboard, other roles pass straight through
class EnsureMarketerIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || $user->user_type !== 'marketer' || $user->marketer?->isApproved()) {
            return $next($request);
        }

        return redirect()
            ->route('marketer.dashboard')
            ->with('error', 'Your marketer account is not approved yet.');
    }
}
