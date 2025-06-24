<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\InvoicePaymentFailed;
use App\Listeners\HandleInvoicePaymentFailed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */

    //We want to add the custom listner here, for when the charge fails.
    protected $listen = [
        \Laravel\Cashier\Events\WebhookReceived::class => [
            \App\Listeners\ChargeFailed::class,
        ],
    ];
    
}
