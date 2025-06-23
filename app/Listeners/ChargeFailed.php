<?php

namespace App\Listeners;

use Laravel\Cashier\Events\InvoicePaymentFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;

use App\Models\Admin;

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
            Log::info("hiere");
            Log::warning($admin->email);
        }
    }
}
