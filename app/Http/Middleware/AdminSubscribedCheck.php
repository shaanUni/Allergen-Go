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
        //Dont enforce on local environments
        if(app()->environment('local')){
            return $next($request);
        }

        $admin = Auth::guard('admin')->user();

        if (!$admin || !$admin->subscribed('default')) {
            return redirect()->route('admin.register')->with('error', 'You must be subscribed to access this area.');
        }

        return $next($request);
    }
}
