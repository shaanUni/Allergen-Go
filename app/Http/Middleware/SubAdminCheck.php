<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//if the user logged in is a sub-admin from an orginisation, they shouldn't be able to access certain pages
class SubAdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();
        $isChild = isset($admin->super_admin_id);
        
        //if the logged in admin is a sub admin, keep them away from certain pages
        if(($isChild) && ($request->routeIs('admin.account') || $request->routeIs('admin.dish-share.*'))){
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
