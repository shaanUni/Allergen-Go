<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

use App\Notifications\accountCreated;
use Carbon\Carbon;

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
        return $admin->newSubscription('default', config('services.stripe.price_id')) // 2nd param is price ID
            ->trialDays(30)
            ->checkout([
                'success_url' => route('admin.subscription.success'),
                'cancel_url' => route('admin.register'),
            ]);
    }

    public function subscriptionSuccess(Request $request)
    {
        $adminId = session('pending_admin_id');
        
        if ($adminId) {
            Auth::guard('admin')->loginUsingId($adminId);  
            session()->forget('pending_admin_id');
            session(['new_user' => 'true']);   
            $admin = Admin::where('id', $adminId);        
            $admin->account_delete_date = null;
            $admin->save();
            return redirect()->route('admin.dashboard')->with('success', 'Account created and subscription active!');
        }
    
        return redirect()->route('admin.login')->with('error', 'Session expired or invalid.');
    }
}
