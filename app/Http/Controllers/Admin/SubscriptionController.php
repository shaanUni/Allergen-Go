<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;
use Stripe\Exception\CardException;
use Stripe\Exception\ApiErrorException;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

use App\Notifications\accountDeleted;

class SubscriptionController extends Controller
{

    public function cancelSubscription()
    {
        $admin = Auth::guard('admin')->user()->fresh();

        //Grab the local Subscription record 
        $subscription = $admin->subscription('default');

        if (!$subscription || !$subscription->valid()) {
            return back()->with('error', 'No active subscription found.');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        //Find subscription
        $stripeSub = $stripe->subscriptions->retrieve($subscription->stripe_id, []);

        //tell stripe to cancel at the end of the period
        $stripe->subscriptions->update($subscription->stripe_id, [
           'cancel_at_period_end' => true,
        ]);
        
        //$stripe->subscriptions->cancel($subscription->stripe_id);
        
        //This will go in the admin table, so they can se when subscription expires, so convert to correct format
        $dateForDb = Carbon::parse($stripeSub->current_period_end)->toDateString();
        //$dateForDb = Carbon::parse(now())->toDateString();
        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);

        //gooodbye email
        $date = Carbon::parse($stripeSub->current_period_end)->format('F j, Y');
        $admin->notify(new accountDeleted($date));

        $admin->account_delete_date = $dateForDb;
        $admin->save();

        //update local record to reflect period end
        $subscription->fill([
            'ends_at' => $periodEnd,
            'stripe_status' => 'canceled',
        ])->save();

        //If they are cancelling with a failed payment, reset the fact they have a failed payment.
        //This can prevent the second email from being sent
        if ($admin->payment_failed) {
            $admin->payment_failed = false;
            $admin->failed_payment_date = null;
            $admin->save();
        }


        return back()->with('success', 'Subscription canceled. You will retain access until '
            . Carbon::parse($stripeSub->current_period_end)->format('F j, Y'));

    }

    public function resubscribe(Request $request)
    {

        $admin = Auth::guard('admin')->user()->fresh();

        //Check if the user is still subscribed 
        if ($admin->account_delete_date == null) {
            return back()->with('error', 'You already have a valid subscribtion.');
        }

        // Use default or fallback payment method
        $defaultMethod = $admin->default_payment_method;

        //If no default method, use the first payment method found from the user
        if (!$defaultMethod) {
            $fallbackMethod = $admin->paymentMethods()->first();
            if ($fallbackMethod) {
                $defaultMethod = $fallbackMethod;
            }
        }

        //If we have an exsisting card we can use
        if ($defaultMethod) {
            $rePurchase = self::reSubscribeWithExistingPayment($admin, $defaultMethod->id);
            //Declined
            if ($rePurchase == 'fail') {
                return back()->with('error', 'Payment method failed. Go to the accounts page to update your card details');
            }
            return redirect()->route('admin.subscription.success');
        }


        //Below code is when no cards are linked to the user, so they have to enter one

        session(['pending_admin_id' => $admin->id]);

        return $admin->newSubscription('default', config('services.stripe.price_id'))
            ->checkout([
                'success_url' => route('admin.subscription.success'),
                'cancel_url' => route('admin.unsubscribed'),
            ]);

    }
    public function updateCard(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();
        $paymentMethodId = $request->input('payment_method');

        // Tell Cashier to update the default payment method on Stripe
        $admin->updateDefaultPaymentMethod($paymentMethodId);

        return back()->with('success', 'Your card has been updated successfully.');
    }


    public function makeDefault($paymentMethod)
    {
        $admin = Auth::guard('admin')->user()->fresh();

        try {
            $admin->updateDefaultPaymentMethod($paymentMethod);
            $admin->refresh();

            return back()->with('success', 'Default payment method updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to set default: ' . $e->getMessage());
        }
    }

    public function deletePaymentMethod($paymentMethod)
    {
        $admin = Auth::guard('admin')->user()->fresh();

        try {
            $admin->deletePaymentMethod($paymentMethod);
            return back()->with('success', 'Payment method deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    public static function reSubscribeWithExistingPayment($admin, $defaultMethod)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $priceId = config('services.stripe.price_id');
        $paymentMethod = $defaultMethod;


        $stripe->paymentMethods->attach($paymentMethod, [
            'customer' => $admin->stripe_id,
        ]);

        //Use the payment method stored locally in DB
        $stripe->customers->update($admin->stripe_id, [
            'invoice_settings' => ['default_payment_method' => $paymentMethod],
        ]);

        // Create the subscription 
        $sub = $stripe->subscriptions->create([
            'customer' => $admin->stripe_id,
            'items' => [['price' => $priceId]],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
            'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
        ]);

        // 3) Grab the invoice and its PaymentIntent
        $invoice = $sub->latest_invoice;              // an Invoice object
        $pi = $invoice->payment_intent;          // a PaymentIntent object

        //If SCA is required, hand back the client_secret for stripe.js
        if ($pi && $pi->status === 'requires_action') {
            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $pi->client_secret,
            ]);
        }
       
        try {
        
            // Otherwise, pay it immediately server-side
            $paid = $stripe->invoices->pay($invoice->id, [
                'expand' => ['payment_intent'],
            ]);

            //Needed to allow access to app
            session(['pending_admin_id' => $admin->id]); // needed for the subscription success route
            $admin->account_delete_date = null; // nullify this so the middleware knows it is no longer cancelled
            $admin->save();
        } catch (CardException $e) {
            // A declined card — inspect $e->getError() if you want more detail
            $declineCode = $e->getError()->decline_code;
            $message = $e->getError()->message;

            return 'fail';

        } catch (ApiErrorException $e) {
            // Any other Stripe API problem
            return 'fail';
        }

    }


}
/*

$admin = Admin::findOrFail(88);
$subscription = $admin->subscription('default');
dd($subscription);
*/