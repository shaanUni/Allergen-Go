<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use App\Notifications\AdminResetPassword;

class Admin extends Authenticatable
{
    use Notifiable;

    //for stripe
    use Billable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'payment_failed',
        'account_delete_date',
        'failed_payment_date',
        'default_payment_method',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function stripe()
    {
        return app(\Stripe\StripeClient::class);
    }


    //one to many relationship with dishes (one restaurant can have many dishes)
    public function dishes()
    {
        return $this->hasMany(Dishes::class);
    }

    public function allergenCount()
    {
        return $this->hasMany(AllergenCount::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    public function subscriptions()
    {
        return $this->hasMany(\Laravel\Cashier\Subscription::class, 'user_id');
    }

}
