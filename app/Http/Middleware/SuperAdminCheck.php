<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//if the user logged in is the super admin, they should only be able to access the superadmin dashboard
class SuperAdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();
        
        //if the logged in admin is a super admin, and he is straying away from the super admin pages, send him back
        if($admin && $admin->super_admin && !$request->routeIs('admin.super-admin.*')){
            return redirect()->route('admin.super-admin.dashboard');
        }

        return $next($request);
    }
}
