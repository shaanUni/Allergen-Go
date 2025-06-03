<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session(['pending_admin_id' => $admin->id]);

        // Redirect to Stripe Checkout
        return $admin->newSubscription('default', 'price_1RVu5hCtfDW7CkKEgj91o3ZK') // 2nd param is price ID
            ->checkout([
                'success_url' => route('admin.dashboard'),
                'cancel_url' => route('admin.register'),
            ]);
    }

    public function subscriptionSuccess(Request $request)
    {
        $adminId = session('pending_admin_id');
        
        if ($adminId) {
            Auth::guard('admin')->loginUsingId($adminId);  
            session()->forget('pending_admin_id');
            return redirect()->route('admin.dashboard')->with('success', 'Account created and subscription active!');
        }
    
        return redirect()->route('admin.login')->with('error', 'Session expired or invalid.');
    }
}
