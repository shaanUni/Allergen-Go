<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

use SimpleSoftwareIO\QrCode\Facades\QrCode;


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

        //Double check it is null, (double check because the front end only shows the button if it is null, so this is an extra precaution)
        if ($restaurantCode == null) {

            //Keep generating a random string, until it is unique and not found elswhere in the DB
            do {
                $randomCode = Str::random(16);
                //The check for uniqueness
            } while (Admin::where('restaurant_code', $randomCode)->exists());


            //New variable instance of the code so we can save
            $newRestaurantCode = Admin::find(Auth::guard('admin')->id());

            //set the code to the string and store
            $newRestaurantCode->restaurant_code = $randomCode;
            $newRestaurantCode->save();
        }

        return redirect()->route('admin.dashboard')->with('success', 'New code Generated.');
    }


    public function qrCode()
    {
        // get exsisting, unique restaurant code
        $restaurantCode = $this->getRestaurantCode();

        //URL for qr code
        $url = url('/user/qr/' . $restaurantCode);

        return view('admin.qrcode', compact('url', 'restaurantCode'));
    }

    public function account()
    {
        $admin = Auth::guard('admin')->user()->fresh();

        //Grab the local Subscription record 
        $subscription = $admin->subscription('default');
        $status = $subscription->stripe_status;
        $date = Carbon::parse($subscription->ends_at);

        $cancelled = "";

        if($status == 'canceled'){
            $cancelled = "true";
        }

        return view('admin.account', ['cancelled' => $cancelled, 'date' => $date]);
    }

    private function getRestaurantCode()
    {
        //Get the unique restaurant code of the restaurant currently logged in
        return Admin::find(Auth::guard('admin')->id())->restaurant_code;
    }
}
