<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    //
    public function checkout()
    {
        $admin = Auth::guard('admin')->user();

        return $admin->newSubscription('default', 'price_1RVu5hCtfDW7CkKEgj91o3ZK')
            ->checkout([
                'success_url' => route('admin.dashboard') . '?subscribed=1',
                'cancel_url' => route('user.search'),
            ]);
    }

    public function cancelSubscription()
    {
        $admin = Auth::guard('admin')->user()->fresh();

        // 1) Grab the local Subscription record (this is the Eloquent model row).
        $subscription = $admin->subscription('default');

        if (!$subscription || !$subscription->valid()) {
            return back()->with('error', 'No active subscription found.');
        }

        // 2) Instantiate StripeClient directly (so we know it's never null).
        //    We're pulling the secret from config/services.php → 'stripe.secret'.
        $stripe = new StripeClient(config('services.stripe.secret'));

        // 3) Retrieve the real Stripe subscription so we can see the current_period_end.
        //    (This is optional, but it’s how you can set "ends_at" to the true end date.)
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        // 4) Ask Stripe to cancel *at period end* (so the user retains access until the billing cycle ends).
        //    You can pass 'invoice_now' => false if you do not want to invoice for the current period,
        //    but the main thing is to tell Stripe to cancel at the end of the period:
        $stripe->subscriptions->update($subscription->stripe_id, [
            'cancel_at_period_end' => true,
        ]);

        // 5) Now update your local DB row so Laravel knows this subscription is “ending” at period end.
        //    Cashier normally does this for you in its own cancel() method, but since we’re doing it manually,
        //    we pull the timestamp from Stripe’s response:
        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);

        $subscription->fill([
            'ends_at' => $periodEnd,
            'stripe_status' => 'canceled',
        ])->save();

        return back()->with('success', 'Subscription canceled. You will retain access until '
            . $periodEnd->toDayDateTimeString() . '.');

    }

}
