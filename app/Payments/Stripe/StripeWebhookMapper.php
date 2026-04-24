<?php

/*

app/Payments/Stripe/StripeWebhookMapper.php

This is the “adapter”
This is the only place Stripe event names appear.

All your Stripe SDK knowledge is preserved

Your billing extraction logic moves to PaymentReconciliationService

Your DB writes become idempotent

Frontend confirmation becomes best-effort, not authoritative

HUB2 can plug in with:

a new webhook route

a new mapper

zero controller changes


*/


namespace App\Payments\Stripe;

use App\Enums\PaymentStatus;

class StripeWebhookMapper
{
    public function mapEventToPaymentStatus(string $eventType): int
    {
        return match ($eventType) {
            'payment_intent.succeeded'      => PaymentStatus::COMPLETED->value,
            'payment_intent.payment_failed' => PaymentStatus::FAILED->value,
            'charge.refunded'               => PaymentStatus::REFUNDED->value,
            default                          => PaymentStatus::PENDING->value,
        };
    }
}