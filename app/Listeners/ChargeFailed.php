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

//for when card is declined
class ChargeFailed
{
    public function handle(WebhookReceived $event)
    {
        
        if ($event->payload['type'] === 'charge.failed') {
            $data = $event->payload['data']['object'];
            $email = $data['billing_details']['email'] ?? 'unknown';
            $amount = $data['amount'] ?? 0;

            $admin = Admin::where('email', $email)->first();
            $admin->payment_failed = true;
            $admin->save();

            $admin->notify(new FailedPayment());

            Log::info("payment failed");
            Log::warning($admin->email);
        }

        if ($event->payload['type'] === 'invoice.payment_succeeded') {
            $invoice = $event->payload['data']['object'];
            $customerId = $invoice['customer'];
            
            $email = $invoice['customer_email'];
            $amountPaid = $invoice['amount_paid'] ?? 0;
            
            $admin = Admin::where('email', $email)->first();
            
            Log::info("payment worked");
            Log::warning($admin->email);
        }
    }
}
