<?php
// app/Http/Middleware/CheckSubscription.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        // $user = Auth::user();

        // if (!$user || $user->user_type !== 'vendor') {
        //     return $next($request);
        // }

        // if ($user->hasExpiredSubscription()) {
        //     $protectedRoutes = ['vendor.dashboard', 'products.create', 'products.store'];

        //     if (in_array($request->route()->getName(), $protectedRoutes)) {
        //         return redirect()->route('vendor.plans')
        //             ->with('warning', 'Your subscription has expired. Please renew to continue using vendor features.');
        //     }
        // }

        // if ($request->routeIs('products.create') && !$user->canAddMoreProducts()) {
        //     return redirect()->route('vendor.dashboard')
        //         ->with('error', 'You have reached your product limit. Please upgrade your plan.');
        // }

        return $next($request);
    }
}
