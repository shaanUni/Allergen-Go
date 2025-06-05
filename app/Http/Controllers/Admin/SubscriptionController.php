<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    //
    public function checkout()
    {
        $admin = Auth::guard('admin')->user();

        return $admin->newSubscription('default', 'price_1RVu5hCtfDW7CkKEgj91o3ZK')
            ->checkout([
                'success_url' => route('admin.dashboard') . '?subscribed=1',
                'cancel_url' => route('user.search'),
            ]);
    }

    public function cancelSubscription()
    {
        $admin = Auth::guard('admin')->user()->fresh();

        if (!$admin->hasStripeId()) {
            $admin->createAsStripeCustomer();
        }

        $subscription = $admin->subscription('default');

        if ($subscription && $subscription->valid()) {
           // dd([
             //   'subscription' => get_class($subscription),
              //  'has_stripe_method' => method_exists($admin, 'stripe'),
               // 'stripe_client' => $admin->stripe(),
            //]);
            
            $subscription->cancel();
            return back()->with('success', 'Subscription canceled.');
        }

        return back()->with('error', 'No active subscription found.');

    }

}
