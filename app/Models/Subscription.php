<?php

namespace App\Models;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public function owner()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
