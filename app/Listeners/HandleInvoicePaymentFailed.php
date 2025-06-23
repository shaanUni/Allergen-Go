<?php

namespace App\Listeners;

use Laravel\Cashier\Events\InvoicePaymentFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleInvoicePaymentFailed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        $invoice = $event->invoice;
        Log::info('Invoice payment failed: ' . $invoice->id);

        // You can get the user like this
        $user = $event->billable;

        // Flag the user, send email, etc.
        Log::info('User with failed payment: ' . $user->email);
    }
}
