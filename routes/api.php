<?php
use App\Http\Controllers\Psp\PspWebhookController;

Route::post('/psp/webhooks/stripe', [PspWebhookController::class, 'handleStripe']);
