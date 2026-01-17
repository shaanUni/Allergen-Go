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

use Log;

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

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['email' => 'Invalid email address.']);
        }
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session(['pending_admin_id' => $admin->id]);

        Log::info('here');        
        
        // Redirect to Stripe Checkout
        return $admin->newSubscription('default', config('services.stripe.price_id')) // 2nd param is price ID
            ->trialDays(config('service-info.trial_period'))
            ->checkout([
                'success_url' => route('admin.subscription.success'),
                'cancel_url' => route('admin.register'),
            ]);
    }

    public function subscriptionSuccess(Request $request)
    {
        Log::info('now here');        

        $adminId = session('pending_admin_id');
        Log::info("admin id {$adminId}");
        if ($adminId) {
            Log::info('inside admin id');        

            Log::info('before login', [
                'session_id' => session()->getId(),
                'admin_check' => Auth::guard('admin')->check(),
              ]);
              
              Auth::guard('admin')->loginUsingId($adminId);
              
              Log::info('after login', [
                'session_id' => session()->getId(),
                'admin_check' => Auth::guard('admin')->check(),
                'admin_id' => Auth::guard('admin')->id(),
              ]);
              
              $request->session()->regenerate();
              
              Log::info('after regenerate', [
                'session_id' => session()->getId(),
                'admin_check' => Auth::guard('admin')->check(),
                'admin_id' => Auth::guard('admin')->id(),
              ]);
              
            session()->forget('pending_admin_id');
            session(['new_user' => 'true']);   
            $admin = Admin::where('id', $adminId)->first();        
            $admin->account_delete_date = null;
            $admin->save();

            return redirect()->route('admin.dashboard')->with('success', 'Account created and subscription active!');
        }
        Log::info("still here");
        return redirect()->route('admin.login')->with('error', 'Session expired or invalid.');
    }
}
