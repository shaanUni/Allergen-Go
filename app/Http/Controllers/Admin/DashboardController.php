<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\InitAccountPageInfo;
use App\Models\Admin;

use App\Models\IpData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use PHPUnit\TextUI\XmlConfiguration\RemoveRegisterMockObjectsFromTestArgumentsRecursivelyAttribute;
use Stripe\StripeClient;
use Illuminate\Notifications\Notifiable;

use App\Notifications\accountCreated;
use App\Notifications\FailedPayment;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function index()
    {

        $admin = Auth::guard('admin')->user()->fresh();
        
        $ip = request()->ip();

        //If the user just made an account, and they are seeing dashboard for the first time
        if(session('new_user')){
            
            $date = Carbon::today();

            $ip = IpData::create([
                'admin_id' => $admin->id,
                'ip_address' => $ip,
                'date_of_first_switch' => $date,
            ]);

            //Card details, dates for next payment
            InitAccountPageInfo::dispatch($admin);

            //send welcome email
            SendWelcomeEmail::dispatch($admin)->delay(now()->addMinute());

            //$this->generate();

            session()->forget('new_user');
        }

        $showIpForm = true;

        //get the unique code
        $restaurantCode = $this->getRestaurantCode();
        return view(
            'admin.dashboard',
            ['restaurant_code' => $restaurantCode]
        );
    }

    //Generate a new unique restaurant code
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

        //When the admin next has to pay
        $date = $admin->current_period_end;

        if($date != null){
            $date = Carbon::parse($date)->format('F j, Y');
        }

        //If the account has been cancelled
        if ($admin->account_delete_date != null) {
            $date = Carbon::parse($admin->account_delete_date)->format('F j, Y');
        }

        // Create a SetupIntent for this user. Cashier will set up the Stripe customer automatically if needed.
        // The SetupIntent’s client_secret is used by Stripe.js on the front-end.
        $intent = $admin->createSetupIntent();

        $defaultMethod = $admin->defaultPaymentMethod();
        $paymentMethods = $admin->paymentMethods();

        $cancelled = "";

        if ($admin->account_delete_date != null) {
            $cancelled = "true";
        }

        $invoices = $admin->invoices();

        return view('admin.account', [
            'cancelled' => $cancelled,
            'date' => $date,
            'invoices' => $invoices,
            'intent' => $intent,
            'defaultMethod' => $defaultMethod,
            'paymentMethods' => $paymentMethods,
            'admin' => $admin,
        ]);
    }
  
    private function getRestaurantCode()
    {
        //Get the unique restaurant code of the restaurant currently logged in
        return Admin::find(Auth::guard('admin')->id())->restaurant_code;
    }
}

