<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

use App\Notifications\accountCreated;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        //This is in memory when a new user was just created, so send them a welcome email with details about their trial
        if (session('new_user')) {
            //This should onyl ever happen once
            session()->forget('new_user');

            $admin = Auth::guard('admin')->user()->fresh();
            $subscription = $admin->subscription('default');

            //Format date for when free trial ends
            $date = Carbon::parse($subscription->trial_ends_at)->format('F j, Y');
            
            //welcome email
            $admin->notify(new accountCreated($date));
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
        $date = Carbon::parse($admin->account_delete_date)->format('F j, Y');

        // Create a SetupIntent for this user. Cashier will set up the Stripe customer automatically if needed.
        // The SetupIntent’s client_secret is used by Stripe.js on the front-end.
        $intent = $admin->createSetupIntent();

        // Optionally, you can pass in the current default payment method (so the user sees “Current card: **** 4242”).
        $defaultMethod = $admin->defaultPaymentMethod();

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
        ]);
    }
    public function updateCard(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();
        $paymentMethodId = $request->input('payment_method');

        // Tell Cashier to update the default payment method on Stripe
        $admin->updateDefaultPaymentMethod($paymentMethodId);

        // Optionally: If you want to bill them immediately (e.g. for an invoice),
        // you could create an invoice here. But most of the time you just update the card
        // and let the next subscription renewal or invoice hit this new card.
        //
        // Example (if you have an open invoice you want to immediately pay):
        // $invoice = $admin->invoice(); // invoices any pending balance
        //
        // Flash a success message and redirect back to the account page:
        return back()->with('success', 'Your card has been updated successfully.');
    }
    private function getRestaurantCode()
    {
        //Get the unique restaurant code of the restaurant currently logged in
        return Admin::find(Auth::guard('admin')->id())->restaurant_code;
    }
}

