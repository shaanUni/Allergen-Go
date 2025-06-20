<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;
use Illuminate\Notifications\Notifiable;

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
        $dateForDb = Carbon::parse($stripeSub->current_period_end)->toDateString();
        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);

        //gooodbye email
        $date = Carbon::parse($stripeSub->current_period_end)->format('F j, Y');
        $admin->notify(new accountDeleted($date));
        
        $admin->account_delete_date = $dateForDb; 
        $admin->save();
        
        //update local record to reflect period end
        $subscription->fill([
            'ends_at' => $periodEnd,
            'stripe_status' => 'canceled',
        ])->save();

        
        return back()->with('success', 'Subscription canceled. You will retain access until '
            . Carbon::parse($stripeSub->current_period_end)->format('F j, Y'));

    }

}
/*

$admin = Admin::findOrFail(12);
$subscription = $admin->subscription('default');
dd($subscription);
*/