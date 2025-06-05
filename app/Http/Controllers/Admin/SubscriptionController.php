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
    //
    public function checkout()
    {
        $admin = Auth::guard('admin')->user();

        return $admin->newSubscription('default', config('services.stripe.price_id'))
            ->trialDays(30)
            ->checkout([
                'success_url' => route('admin.dashboard') . '?subscribed=1',
                'cancel_url' => route('user.search'),
            ]);
    }

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
        $stripe->subscriptions->update($subscription->stripe_id, [
            'cancel_at_period_end' => true,
        ]);

        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);
        //gooodbye email
        $date = Carbon::parse($stripeSub->current_period_end)->format('F j, Y');
        $admin->notify(new accountDeleted($periodEnd));

        //update local record to reflect period end
        $subscription->fill([
            'ends_at' => $periodEnd,
            'stripe_status' => 'canceled',
        ])->save();

        
        return back()->with('success', 'Subscription canceled. You will retain access until '
            . $periodEnd->toDayDateTimeString() . '.');

    }

}
