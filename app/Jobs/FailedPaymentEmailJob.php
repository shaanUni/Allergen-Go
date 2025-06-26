<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

use App\Notifications\accountCreated;
use App\Notifications\FailedPayment;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;


class FailedPaymentEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        //Get all the admins who have a payment failed date, have an active subscription, and haven't been sent the email yet
        $admins = Admin::where('payment_failed', true)
        ->whereNull('account_delete_date')
        ->where('reminder_email_sent', false)
        ->get();


        foreach ($admins as $admin) {
            //  needs to go inJob
            //If 3 or more days elapsed since they failed, send the final reminder email
            $thresholdDate = Carbon::parse($admin->failed_payment_date)->addDays(3);
            //The date when the account will be closed
            $emailDate = Carbon::parse($admin->failed_payment_date)->addDays(7);
            $emailDate = Carbon::parse($emailDate)->format('F j, Y');

            if (now()->greaterThanOrEqualTo($thresholdDate)) {
                $admin->reminder_email_sent = true;
                Log::info('imhere');
                $admin->save();
                $admin->notify(new FailedPayment($emailDate));
            }

        }
    }
}
