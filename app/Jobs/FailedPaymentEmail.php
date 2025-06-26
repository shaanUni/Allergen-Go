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


class FailedPaymentEmail implements ShouldQueue
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
        //
        $admins = Admin::where('payment_failed')->get();

        foreach ($admins as $admin) {
            Log::info($admin->name);
            /*
            //  needs to go inJob
            if ($admin->payment_failed) {
                //If 3 or more days elapsed since they failed, send the final reminder email
                $thresholdDate = Carbon::parse($admin->failed_payment_date)->addDays(3);
                //The date when the account will be closed
                $emailDate = Carbon::parse($admin->failed_payment_date)->addDays(7);
                $emailDate = Carbon::parse($emailDate)->format('F j, Y');

                if (now()->greaterThanOrEqualTo($thresholdDate)) {
                    $admin->notify(new FailedPayment($emailDate));
                }
            }
            */
        }
    }
}
