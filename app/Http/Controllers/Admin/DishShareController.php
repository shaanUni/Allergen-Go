<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\Dishes;
use App\Models\DishShare;
use App\Models\Searches;
use App\Models\AllergenCount;
use App\Models\Opt_in_logs;
use App\Models\SelectedDishes;

use App\Services\AllergenService;
use App\Services\SearchService;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DishShareNotification;
use Illuminate\Notifications\Notifiable;

class DishShareController extends Controller
{

    public function index()
    {
        return view(
            'admin.dish-share',
        );
    }


    public function initShare(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $adminId = Auth::guard('admin')->id();

        //The email that the logged in admin wants to share with
        $email = $request->input('email');

        //if the admin trying to share is already part of a dish share as a child
        $alreadyChild = DishShare::where('child_admin_id', $adminId)->where('declined', false)->get();

        if(count($alreadyChild) != 0){
            return back()->withErrors(['email' => 'You are unable to start a dish share, as you are already involved in one as a child']);
        }

        //if the person we are sharing with is already involved, don't share with them
        $childAlreadyHasParent = DishShare::where('child_admin_id', $email)->where('declined', false)->get();

        if(count($childAlreadyHasParent) != 0){
            return back()->withErrors(['email' => 'You are unable to start a dish share, as the admin you are sharing with already has a dish share.']);
        }
        
        //See if the account they want to share with exsists
        $adminReceiverAccount = Admin::where('email', $email)->first();
        if(!$adminReceiverAccount){
            return back()->withErrors(['email' => 'Unknown admin.']);
        }

        //logged in admins id
        $admin_id = Admin::find($adminId)->id;

        //unique identifier for dish share record
        $uuid = Str::uuid();

        //check that they are not trying to share with themselves
        if($adminReceiverAccount->id != $admin_id){
            $dishShare = DishShare::create([
                'parent_admin_id' => $admin_id,
                'child_admin_id' => $adminReceiverAccount->id,
                'status' => false,
                'uuid' => $uuid, 
            ]);

            $childAdmin = $dishShare->childAdmin;
            
            $childAdmin->notify(new DishShareNotification($childAdmin->email, $dishShare->uuid));
            
        } else{
            return back()->withErrors(['email' => 'You can not add yourself.']);
        }

        return back()->with('status', 'Dish share request sent successfully!');
    }

    //Allows the dish receiver to accept
    public function accept($uuid){
        $this->setDishShareStatus($uuid, true);
    }

    //dish receiver wants to decline
    public function decline($uuid){
        $this->setDishShareStatus($uuid, false);
    }

    //If the parent wants to revoke access
    public function delete($child_admin_id){
        $dishShareRecord = DishShare::where('child_admin_id', $child_admin_id)->first();
        $dishShareRecord->delete();
    }

    //Function to set status. If false, they have delcined
    public static function setDishShareStatus($uuid, $bool){
        $dishShare = DishShare::where('uuid', $uuid)->first();
        
        //if the child is about to accept, but they have already become part of a dish share, not allowed
        $otherDishShares = DishShare::where('child_admin_id', $dishShare->child_admin_id)->where('declined', false)->get();
        if($bool == true && count($otherDishShares) != 0){
            return;
        }

        $dishShare->status = $bool;
        $dishShare->save();
    }

}