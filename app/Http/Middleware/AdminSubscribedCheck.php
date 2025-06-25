<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        //allow these routes
        if ($request->routeIs('admin.unsubscribed') || $request->routeIs('admin.subscription.buy')) {
            return $next($request); 
        }

        //Also allow access to account pages, so they can update card if needed
        if ($request->routeIs('admin.account') || $request->routeIs('admin.payment-methods.update-card')) {
            return $next($request); 
        }
        
        //If this date is set, it means the user cancelled their subscription
        if($admin->account_delete_date != null){
            //If the date is LTE (less than or equal) to today, they can not acsess the app
            if(Carbon::parse($admin->account_delete_date)->lte(Carbon::today())){
                return redirect()->route('admin.unsubscribed')->with('error', 'You unsubscribed.');
            }
        }

        //If the account was just created, this will be true
        $newUserStatus = session('new_user');

        if ($newUserStatus != 'true' && (!$admin || !$admin->subscribed('default'))) {
            session()->forget('new_user');
            return redirect()->route('admin.register')->with('error', 'You must be subscribed to access this area.');
        }

        return $next($request);
    }
}
