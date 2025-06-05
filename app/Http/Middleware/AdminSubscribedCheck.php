<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//middleware to ensure admin is subscribed
class AdminSubscribedCheck
{
    public function handle(Request $request, Closure $next)
    {
        // 1) If the current route is the register page, let them through:
        if ($request->routeIs('admin.register')) {
            return $next($request);
        }

        // 2) If they're already logged in as an admin but need to see the checkout page,
        //    let them through so they can subscribe:
        if ($request->routeIs('admin.subscription.checkout') && Auth::guard('admin')->check()) {
            return $next($request);
        }

        // 3) Otherwise, enforce: user must be logged in AND subscribed:
        $admin = Auth::guard('admin')->user();

        if (! $admin || ! $admin->subscribed('default')) {
            return redirect()
                ->route('admin.register')
                ->with('error', 'You must be subscribed to access this area.');
        }

        return $next($request);
    }
}
