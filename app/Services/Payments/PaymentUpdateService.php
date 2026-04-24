<?php
/*

app/Services/Payments/PaymentInitiationService.php

*/
namespace App\Services\Payments;

use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Illuminate\Support\Facades\Log;

class PaymentUpdateService
{
    public function updateAfterValidation(
        string $paymentIntentId,
        string $firstName,
        string $lastName
    ): void {

        // Retrieve PaymentIntent
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

        if (!$paymentIntent) {
            throw new \RuntimeException('PaymentIntent not found');
        }

        // Retrieve Payment Method
        $paymentMethod = $paymentIntent->payment_method
            ? PaymentMethod::retrieve($paymentIntent->payment_method)
            : null;

        $billing = $paymentMethod->billing_details ?? (object)[];

        // Update Stripe customer (optional but clean)
        if ($paymentIntent->customer) {
            Customer::update(
                $paymentIntent->customer,
                array_filter([
                    'name' => trim($firstName . ' ' . $lastName),
                    'email' => $billing->email ?? null,
                    'phone' => $billing->phone ?? null,
                ])
            );
        }
    }
}
