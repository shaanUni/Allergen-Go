<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

use App\Notifications\accountDeleted;

class SubscriptionController extends Controller
{

    public function cancelSubscription()
    {
        $admin = Auth::guard('admin')->user()->fresh();

        //Grab the local Subscription record 
        $subscription = $admin->subscription('default');

        if (!$subscription || !$subscription->valid()) {
            return back()->with('error', 'No active subscription found.');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        //Find subscription
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        //tell stripe to cancel at the end of the period
        //$stripe->subscriptions->update($subscription->stripe_id, [
        //   'cancel_at_period_end' => true,
        //]);
        $stripe->subscriptions->cancel($subscription->stripe_id);
        //This will go in the admin table, so they can se when subscription expires, so convert to correct format
        //$dateForDb = Carbon::parse($stripeSub->current_period_end)->toDateString();
        $dateForDb = Carbon::parse(now())->toDateString();
        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);

        //gooodbye email
        $date = Carbon::parse($stripeSub->current_period_end)->format('F j, Y');
        //$admin->notify(new accountDeleted($date));

        $admin->account_delete_date = $dateForDb;
        $admin->save();

        //update local record to reflect period end
        $subscription->fill([
            'ends_at' => $periodEnd,
            'stripe_status' => 'canceled',
        ])->save();

        //If they are cancelling with a failed payment, reset the fact they have a failed payment.
        //This can prevent the second email from being sent
        if ($admin->payment_failed) {
            $admin->payment_failed = false;
            $admin->failed_payment_date = null;
            $admin->save();
        }


        return back()->with('success', 'Subscription canceled. You will retain access until '
            . Carbon::parse($stripeSub->current_period_end)->format('F j, Y'));

    }

    public function resubscribe(Request $request)
    {
        $admin = Auth::guard('admin')->user()->fresh();
        Log::info('skb');

        if ($admin->account_delete_date != null) {
            Log::info($admin->account_delete_date);
            return back()->with('info', 'You already have an active subscription.');
        }

        $paymentMethod = $request->input('payment_method');

        if (!$paymentMethod) {
            Log::info('shady');
            return back()->with('error', 'No payment method provided.');
        }

        try {
            $admin->newSubscription('default', config('services.stripe.price_id'))
                ->create($paymentMethod);

            $admin->account_delete_date = null;
            $admin->save();
            Log::info('konvict');

            return back()->with('success', 'You have successfully resubscribed.');
        } catch (\Exception $e) {
            Log::info('up front');
            return back()->with('error', 'Error resubscribing: ' . $e->getMessage());
        }
    }


}
/*

$admin = Admin::findOrFail(12);
$subscription = $admin->subscription('default');
dd($subscription);
*/