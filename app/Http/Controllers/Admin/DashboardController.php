<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        //get the unique code
        $restaurantCode = $this->getRestaurantCode();

        return view(
            'admin.dashboard',
            ['restaurant_code' => $restaurantCode,]
        );
    }

    public function generate()
    {
        // get exsisting code
        $restaurantCode = $this->getRestaurantCode();

        //Double check it is null
        if ($restaurantCode == null) {

            //Keep generating a random string, until it is unique and not found elswhere in the DB
            do {
                $randomCode = Str::random(16);
                //The check for uniqueness
            } while (Admin::where('restaurant_code', $randomCode )->exists());


            //New variable instance of the code so we can save
            $newRestaurantCode = Admin::find(Auth::guard('admin')->id());

            //set the code to the string and store
            $newRestaurantCode->restaurant_code = $randomCode;
            $newRestaurantCode->save();
        }

        return redirect()->route('admin.dashboard')->with('success', 'New code Generated.');
    }

    private function getRestaurantCode()
    {
        //Get the unique restaurant code of the restaurant currently logged in
        return Admin::find(Auth::guard('admin')->id())->restaurant_code;
    }
}
