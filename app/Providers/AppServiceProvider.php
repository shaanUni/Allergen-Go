<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use App\Models\Admin;
use Stripe\StripeClient;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Cashier::useCustomerModel(Admin::class);
        //app()->singleton(StripeClient::class, function () {
          //  return new StripeClient(config('services.stripe.secret'));
        //});
    }
}
