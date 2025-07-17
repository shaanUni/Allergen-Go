<?php

namespace App\Jobs;

use Illuminate\Http\Request;

use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;
use Stripe\Exception\CardException;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class RevokeAccess implements ShouldQueue
{
    use Queueable;
    protected $admin;

    /**
     * Create a new job instance.
     */
    public function __construct(Admin $admin)
    {
        //
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('inside');

        //
        $admin = $this->admin;

        //Grab the local Subscription record 
        $subscription = $admin->subscription('default');


        $stripe = new StripeClient(config('services.stripe.secret'));

        //Find subscription
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        //tell stripe to cancel at the end of the period
        $stripe->subscriptions->update($subscription->stripe_id, [
            'cancel_at_period_end' => false,
        ]);

        $stripe->subscriptions->cancel($subscription->stripe_id);

        //This will go in the admin table, so they can se when subscription expires, so convert to correct format
        $dateForDb = Carbon::parse(now())->toDateString();


        $admin->account_delete_date = $dateForDb;
        $admin->save();

        //update local record to reflect period end
        $subscription->fill([
            'ends_at' => now(),
            'stripe_status' => 'canceled',
        ])->save();

        //If they are cancelling with a failed payment, reset the fact they have a failed payment.
        //This can prevent the second email from being sent
        if ($admin->payment_failed) {
            $admin->payment_failed = false;
            $admin->failed_payment_date = null;
            $admin->save();
        }
    }
}
