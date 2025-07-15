<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;
use Stripe\StripeClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


class InitAccountPageInfo implements ShouldQueue
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
        $admin = $this->admin;

        //Grab the local Subscription record 
        $subscription = $admin->subscription('default');

        $stripe = new StripeClient(config('services.stripe.secret'));

        //Find subscription
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        //Next payment date
        $date = Carbon::createFromTimestamp($stripeSub->current_period_end);

        $admin->current_period_end = $date;
        $admin->save();


    }
}
