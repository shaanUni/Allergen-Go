<?php

namespace App\Http\Middleware;

use App\Jobs\RevokeAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        
        if ($request->routeIs('admin.account') || $request->routeIs('admin.account.payment-methods.update-card') || $request->routeIs('admin.account.payment-methods.delete')) {
            return $next($request); // Don't redirect loop into itself
        }
        
        $admin = Auth::guard('admin')->user();

        //If they missed a payment
        if($admin->payment_failed){
            //Get the date of their first failed payment, and add a week
            $date = Carbon::parse($admin->failed_payment_date)->addDays(7);

            //if a week or more has elapsed, revoke their access
            if(now()->greaterThanOrEqualTo($date)){
                RevokeAccess::dispatch($admin);
            }
        }

        return $next($request);
    }
}
