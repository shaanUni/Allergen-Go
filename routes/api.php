<?php
use App\Http\Controllers\StripeWebhookController;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
Route::post('/stripe/webhook', function () {
    file_put_contents(storage_path('logs/hit.txt'), now());
    return response('Webhook test route', 200);
});
