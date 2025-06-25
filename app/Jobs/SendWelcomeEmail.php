<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\accountCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function handle()
    {
        //Get the admins subscription table
        $subscription = $this->admin->subscription('default');

        //Send a welcome email, including the date their trial is over
        if ($subscription && $subscription->trial_ends_at) {
            $date = Carbon::parse($subscription->trial_ends_at)->format('F j, Y');
            $this->admin->notify(new accountCreated($date));
        } else {
            Log::warning("No subscription found for Admin ID: {$this->admin->id}");
        }
    }
}
