<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
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

        $admins = Admin::whereNotNull('account_delete_date')->get();
        Log::info("psycho");

        foreach ($admins as $admin) {
            Log::info($admin->name);
            Log::info("killer");
        }

        $admin = Auth::guard('admin')->user()->fresh();

        if(session('new_user')){
            SendWelcomeEmail::dispatch($admin)->delay(now()->addMinute());
            session()->forget('new_user');
        }

        //  needs to go inJob
        if($admin->payment_failed){
            //If 3 or more days elapsed since they failed, send the final reminder email
            $thresholdDate = Carbon::parse($admin->failed_payment_date)->addDays(3);
            //The date when the account will be closed
            $emailDate = Carbon::parse($admin->failed_payment_date)->addDays(7);
            $emailDate = Carbon::parse($emailDate)->format('F j, Y');

            if(now()->greaterThanOrEqualTo($thresholdDate)){
                $admin->notify(new FailedPayment($emailDate));
            }
        }

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

        $stripe = new StripeClient(config('services.stripe.secret'));
        //Find subscription
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        //When the admin next has to pay
        $date = Carbon::createFromTimestamp($stripeSub->current_period_end);
        $date = Carbon::parse($date)->format('F j, Y');

        //If the account has been cancelled
        if ($admin->account_delete_date != null) {
            $date = Carbon::parse($admin->account_delete_date)->format('F j, Y');
        }

        // Create a SetupIntent for this user. Cashier will set up the Stripe customer automatically if needed.
        // The SetupIntent’s client_secret is used by Stripe.js on the front-end.
        $intent = $admin->createSetupIntent();

        $stripeCustomer = $stripe->customers->retrieve($admin->stripe_id, []);

        // Save it to your local model (optional if your UI depends on it)
        $admin->default_payment_method = $stripeCustomer->invoice_settings->default_payment_method;
        $admin->save();

        // Optionally, you can pass in the current default payment method (so the user sees “Current card: **** 4242”).
        $defaultMethod = $admin->defaultPaymentMethod();

        $paymentMethods = $admin->paymentMethods();

        $cancelled = "";

        if ($status == 'canceled') {
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

