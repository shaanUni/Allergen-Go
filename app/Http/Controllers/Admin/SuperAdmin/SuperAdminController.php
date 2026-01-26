<?php

namespace App\Http\Controllers\Admin\SuperAdmin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\IpData;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user()->fresh();
        $childrenAccounts = $admin->childAccounts()->get();

        return view(
            'admin.super-admin.dashboard', compact('admin', 'childrenAccounts')
        );
    }

    public function newAdminForm(){
        return view('admin.super-admin.forms.new-admin');
    }

    public function submit(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'city' => 'nullable|string',
            'street' => 'nullable|string',
            'postcode' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = Auth::guard('admin')->user()->fresh();

        //make the child admin account
        $childAdmin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'super_admin_id' => $admin->id,
            'password' => Hash::make($request->password),
        ]);

        //tie it to a location
        Location::create([
            'admin_id' => $childAdmin->id,
            'city' => $request->city,
            'street' => $request->street,
            'postcode' => $request->postcode,
        ]);

        return redirect()->route('admin.super-admin.dashboard')->with('success', 'Account added successfully!');
    }
  
}

