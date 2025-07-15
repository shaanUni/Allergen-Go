<?php

namespace App\Http\Middleware;

use App\Jobs\RevokeAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UnpaidSubscriptionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Dont enforce on local environments
        if(app()->environment('local')){
            return $next($request);
        }
        
        if ($request->routeIs('admin.account') || $request->routeIs('admin.payment-methods.update-card') || $request->routeIs('admin.payment-methods.delete')) {
            return $next($request); // Don't redirect loop into itself
        }
        
        $admin = Auth::guard('admin')->user();

        //If they missed a payment
        if($admin->payment_failed){
            //Get the date of their first failed payment, and add a week
            //$date = Carbon::parse($admin->failed_payment_date)->addDays(7);
            //If a week or more elapsed.
            //if(now()->greaterThanOrEqualTo($date)){
                //return redirect()->route('admin.account')->with('error', 'You need to pay.');
            //}
            RevokeAccess::dispatch();
        }

        return $next($request);
    }
}
