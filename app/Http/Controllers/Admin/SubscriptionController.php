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

        $subscription = $admin->subscription('default');

        if (!$subscription) {
            dd('Subscription object is NULL', $admin->subscriptions()->get());
        }

       // dd('Subscription found', $subscription->toArray());
        if ($admin->subscribed('default')) {
            $admin->subscription('default')->cancel();
            return back()->with('success', 'Subscription canceled. You will retain access until the end of the billing period.');
        }

        return back()->with('error', 'No active subscription found.');
    }

}
