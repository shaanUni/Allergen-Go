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
    protected $listen = [
        InvoicePaymentFailed::class => [
            HandleInvoicePaymentFailed::class,
        ],
    ];
}
