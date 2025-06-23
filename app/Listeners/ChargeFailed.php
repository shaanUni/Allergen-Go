<?php

namespace App\Listeners;

use Laravel\Cashier\Events\InvoicePaymentFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;

use App\Models\Admin;
use Illuminate\Notifications\Notifiable;
use App\Notifications\FailedPayment;
use Carbon\Carbon;


//for when card is declined
class ChargeFailed
{
    public function handle(WebhookReceived $event)
    {

        //If a payment failed
        if ($event->payload['type'] === 'charge.failed') {
            $email = $data['billing_details']['email'] ?? 'unknown';
            //get the admin who's payment failed
            //$admin = Admin::where('email', $email)->first();
            //if it has not failed yet - we need this, as if it has failed beforem the failed payment date would keep getting pushed back
            /*
            if (!$admin->payment_failed) {
                $admin->payment_failed = true;
                $admin->failed_payment_date = Carbon::parse(Carbon::now())->toDateString();
                $admin->save();
                
                //The account will be closed on this date
                $emailDate = Carbon::parse($admin->failed_payment_date)->addDays(7);
                $emailDate = Carbon::parse($emailDate)->format('F j, Y');


                $admin->notify(new FailedPayment($emailDate));
            }
            */
            Log::info("payment failed");
            Log::info($email);
           // Log::warning($admin->email);
        }

        //If a payment fails
        if ($event->payload['type'] === 'invoice.payment_succeeded') {
            $invoice = $event->payload['data']['object'];

            $email = $invoice['customer_email'];

            $admin = Admin::where('email', $email)->first();

            //if this admin currently has a failed payment, change the status to paid
            if ($admin->payment_failed) {
                $admin->payment_failed = false;
            }

        }
    }
}
