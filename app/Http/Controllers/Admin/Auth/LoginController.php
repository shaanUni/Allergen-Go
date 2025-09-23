<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('admin.auth.login');
    }
    
    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user()->fresh();
            
            Auth::logoutOtherDevices($request->password);

            if($admin->ip_data && request()->ip() != $admin->ip_data->ip_address){
                //if date of first switch happened within this week     
                $isWithinPastWeek = $admin->ip_data?->date_of_first_switch?->isBetween(now()->subWeek(), now());     
    
                //if the ip has been changed many times, and the initial swap was within the last week, they are sharing
                if($admin->ip_data->switches >= 5 && $isWithinPastWeek){
                    //sharing
                    $admin->ip_data->account_sharing = true;
                    $admin->ip_data->save();
                    
                //If it has been swapped more than 5 times, but it was over a week ago, reset everything
                } else if($admin->ip_data->switches >= 5 && !$isWithinPastWeek){
                    $admin->ip_data->switches = 0;
                    $admin->ip_data->date_of_first_switch = now();
                    $admin->ip_data->ip_address = request()->ip();
                    $admin->ip_data->save();
                
                //Set new ip and increment if counter is less than 5
                } else if($admin->ip_data->switches < 5){
                    $admin->ip_data->ip_address = request()->ip();
                    $admin->ip_data->increment('switches');
                    $admin->ip_data->save();
                }
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
