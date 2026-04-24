<?php

namespace App\Http\Controllers\Psp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use App\Services\Payments\PaymentReconciliationService;
use App\Payments\Stripe\StripeWebhookMapper;

class PspWebhookController extends Controller
{
    protected PaymentReconciliationService $reconciliationService;
    protected StripeWebhookMapper $mapper;

    public function __construct(
        PaymentReconciliationService $reconciliationService,
        StripeWebhookMapper $mapper
    ) {
        $this->reconciliationService = $reconciliationService;
        $this->mapper = $mapper;
    }

    /**
     * Stripe Webhook Endpoint
     */
    public function handleStripe(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        // 1️⃣ Verify webhook signature
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Throwable $e) {
            Log::warning('Invalid Stripe webhook', [
                'error' => $e->getMessage(),
            ]);
            return response('Invalid', 400);
        }

        // 2️⃣ Allow only final payment events
        $allowedEvents = [
            'payment_intent.succeeded',
            'payment_intent.payment_failed',
            'payment_intent.canceled',
            'payment_intent.processing',
            'charge.succeeded',
            'charge.failed',
        ];

        if (!in_array($event->type, $allowedEvents, true)) {
            Log::debug('Stripe webhook ignored', [
                'event_type' => $event->type,
            ]);
            return response('Ignored', 200);
        }

        // 3️⃣ Extract Stripe object
        $object = $event->data->object;

        // 4️⃣ Resolve canonical PaymentIntent ID
        if (isset($object->payment_intent)) {
            $paymentIntentId = $object->payment_intent;
        } elseif (isset($object->id) && str_starts_with($object->id, 'pi_')) {
            $paymentIntentId = $object->id;
        } else {
            Log::debug('Stripe webhook ignored (no PaymentIntent)', [
                'event_type' => $event->type,
                'object_id'  => $object->id ?? null,
            ]);
            return response('Ignored', 200);
        }

        // 5️⃣ Map Stripe event → internal payment status
        $paymentStatusId = $this->mapper
            ->mapEventToPaymentStatus($event->type);

        Log::warning('Stripe event', [
            'event' => $event->type,
            'mapped_status' => $paymentStatusId,
        ]);

        // 6️⃣ Reconcile (idempotent)
        $this->reconciliationService->reconcileStripePayment(
            stripePaymentIntentId: $paymentIntentId,
            paymentStatusId: $paymentStatusId,
            rawPayload: $event->toArray()
        );

        return response('OK', 200);
    }
}
