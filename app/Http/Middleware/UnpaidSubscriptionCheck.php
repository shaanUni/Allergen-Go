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
        
        if ($request->routeIs('admin.account') || $request->routeIs('admin.payment-methods.update-card') || $request->routeIs('admin.payment-methods.delete')) {
            return $next($request); // Don't redirect loop into itself
        }
        
        $admin = Auth::guard('admin')->user();

        //If they missed a payment
        if($admin->payment_failed){
            Log::info('middleware');
            RevokeAccess::dispatch($admin);
        }

        return $next($request);
    }
}
