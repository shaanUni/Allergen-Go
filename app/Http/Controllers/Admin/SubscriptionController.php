<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\StripeClient;
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
        //$stripe->subscriptions->update($subscription->stripe_id, [
        //   'cancel_at_period_end' => true,
        //]);
        $stripe->subscriptions->cancel($subscription->stripe_id);
        //This will go in the admin table, so they can se when subscription expires, so convert to correct format
        //$dateForDb = Carbon::parse($stripeSub->current_period_end)->toDateString();
        $dateForDb = Carbon::parse(now())->toDateString();
        $periodEnd = Carbon::createFromTimestamp($stripeSub->current_period_end);

        //gooodbye email
        $date = Carbon::parse($stripeSub->current_period_end)->format('F j, Y');
        //$admin->notify(new accountDeleted($date));

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
        
        Log::info('madeit');

        if ($admin->account_delete_date == null) {
        Log::info('this');

            return back()->with('info', 'You already have an active subscription.');
        }
        $admin->createOrGetStripeCustomer();

        // Use default or fallback payment method
        $defaultMethod = $admin->default_payment_method;

        if (!$defaultMethod) {
            Log::info('here');
            $fallbackMethod = $admin->paymentMethods()->first();
            if ($fallbackMethod) {
            Log::info('eyes');
                $defaultMethod = $fallbackMethod;
            }
        }

        if ($defaultMethod) {
            Log::info('kong');
            Log::info($defaultMethod);

            $admin   = Auth::guard('admin')->user()->fresh();
            $stripe  = new StripeClient(config('services.stripe.secret'));
            $priceId = config('services.stripe.price_id');
        
            // 1) If there is an existing 'default' sub in stripe_status = 'incomplete', pay its invoice
            $localSub = $admin->subscription('default');
            if ($localSub && $localSub->stripe_status === 'incomplete') {
                // Retrieve the Stripe invoice + payment_intent
                $invoice = $stripe->invoices->retrieve(
                    $localSub->latestInvoice()->stripe_id,
                    ['expand' => ['payment_intent']]
                );
                $pi = $invoice->payment_intent;
        
                // If SCA required, ask the frontend to confirm
                if ($pi && $pi->status === 'requires_action') {
                    return response()->json([
                        'requires_action'            => true,
                        'payment_intent_client_secret' => $pi->client_secret,
                    ]);
                }
        
                // Otherwise, try to pay server-side:
                $paidInvoice = $stripe->invoices->pay($invoice->id, [
                    'expand' => ['payment_intent'],
                ]);
        
                if ($paidInvoice->status === 'paid') {
                    // subscription is now active
                    return redirect()->route('admin.subscription.success');
                }
        
                abort(500, 'Unable to pay the pending invoice (status: ' . $paidInvoice->status . ')');
            }
        
            // 2) No incomplete invoice: are we receiving a new PaymentMethod?
            $paymentMethodId = $defaultMethod;
            if ($paymentMethodId) {
                // Attach & make default
                $stripe->paymentMethods->attach($paymentMethodId, [
                    'customer' => $admin->stripe_id,
                ]);
                $stripe->customers->update($admin->stripe_id, [
                    'invoice_settings' => [
                        'default_payment_method' => $paymentMethodId,
                    ],
                ]);
        
                // Create the new subscription
                $sub = $stripe->subscriptions->create([
                    'customer'         => $admin->stripe_id,
                    'items'            => [['price' => $priceId]],
                    'payment_settings' => [
                        'save_default_payment_method' => 'on_subscription',
                    ],
                    'expand'           => ['latest_invoice.payment_intent'],
                    'payment_behavior' => 'default_incomplete',
                    'metadata'         => [
                        'owner_model' => get_class($admin),
                        'owner_id'    => $admin->id,
                    ],
                ]);
        
                // Handle SCA if needed
                $pi = $sub->latest_invoice->payment_intent;
                if ($pi && $pi->status === 'requires_action') {
                    return response()->json([
                        'requires_action'            => true,
                        'payment_intent_client_secret' => $pi->client_secret,
                    ]);
                }
        
                return redirect()->route('admin.subscription.success');
            }
        
            // 3) Fallback: no PM yet → Stripe Checkout
            session(['pending_admin_id' => $admin->id]);
        
            return $admin
                ->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('admin.subscription.success'),
                    'cancel_url'  => route('admin.unsubscribed'),
                ]);
        }

        // No payment methods found – fallback to Stripe Checkout
        session(['pending_admin_id' => $admin->id]);

        return $admin->newSubscription('default', config('services.stripe.price_id'))
            ->checkout([
                'success_url' => route('admin.subscription.success'),
                'cancel_url' => route('admin.unsubscribed'),
            ]);
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



}
/*

$admin = Admin::findOrFail(12);
$subscription = $admin->subscription('default');
dd($subscription);
*/