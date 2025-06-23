<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        dd("asd 123");
        Log::info('Received payload', $payload);

        switch ($payload['type']) {
            case 'invoice.payment_failed':
                $invoice = $payload['data']['object'];
                Log::info('Stripe invoice.payment_failed', $invoice);

                // TODO: Find user by customer ID and mark their subscription as past due, notify them, etc.

                break;
            case 'charge.failed':
                $invoice = $payload['data']['object'];
                Log::info('Stripe invoice.payment_failed', $invoice);

                // TODO: Find user by customer ID and mark their subscription as past due, notify them, etc.

                break;

            case 'invoice.payment_succeeded':
                $invoice = $payload['data']['object'];
                Log::info('Stripe invoice.payment_succeeded', $invoice);

                // TODO: Optionally update user status if needed
                break;

            case 'customer.subscription.deleted':
                $subscription = $payload['data']['object'];
                Log::info('Stripe subscription canceled', $subscription);

                // TODO: Deactivate user’s account or notify them

                break;
        }

        return new Response('Webhook Handled', 200);
    }
}
