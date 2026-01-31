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
        'reminder_email_sent',
        'share_dishes',
        'super_admin',
        'super_admin_id',
        'quantity',
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

    public function searches(){
        return $this->hasMany(Searches::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\Laravel\Cashier\Subscription::class, 'user_id');
    }

    public function ip_data(){
        return $this->hasOne(IpData::class);
    }

    public function location(){
        return $this->hasOne(Location::class);
    }
    
    public function childAccounts(){
        return $this->hasMany(self::class, 'super_admin_id', 'id');
    }

    public function parentAccount(){
        return $this->belongsTo(self::class, 'super_admin_id', 'id' );
    }

    //has a organisation reached the limit of sub accounts added
    public function reachedLimit(){
        $childrenAccounts = $this->childAccounts()->get();
        
        //how many sub accounts have they added
        $childrenAccountsCount = count($childrenAccounts);

        //how many did they pay for
        $quantity = $this->quantity;

        //if they reached the limit, don't show them the button to make new accounts
        $reachedLimit = $childrenAccountsCount >= $quantity;

        return $reachedLimit;
    }

}
