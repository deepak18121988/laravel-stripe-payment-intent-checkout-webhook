<?php
/*

app/Services/Payments/PaymentInitiationService.php

*/
namespace App\Services\Payments;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use App\Models\TransactionBookingResale;
use App\Models\PspTransaction;
use App\Models\PspSupportedPaymentMethod;
use Illuminate\Support\Facades\Log;

class PaymentInitiationService
{
    private const STRIPE_PSP_ID = 1;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createStripePaymentIntent(
        int $transactionId,
        int $amount,
        string $currency
        //,string $flow
    ): array {
        $transaction = TransactionBookingResale::findOrFail(
            $transactionId
        );

        // Fetch enabled payment methods (configuration only)
        $paymentMethodTypes = PspSupportedPaymentMethod::where('enabled', 1)
            ->where('psp_vendor_id', self::STRIPE_PSP_ID)
            ->pluck('name')
            ->toArray();

        if (empty($paymentMethodTypes)) {
            throw new \RuntimeException('No Stripe payment methods enabled');
        }

        // Create Stripe customer
        $customer = Customer::create();

        // Create PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount'   => $amount,
            'currency' => $currency,
            'customer' => $customer->id,
            'payment_method_types' => $paymentMethodTypes,
            'metadata' => [
                'transaction_booking_resale_id' => $transaction->id,
            ],
        ]);

        // Create PSP transaction ledger entry
        /*PspTransaction::create([
            'psp_vendor_id' => self::STRIPE_PSP_ID,
            'psp_reference' => $paymentIntent->id,
            'transaction_booking_resale_id' => $transaction->id,
            'psp_status' => $paymentIntent->status,
            'payload' => json_encode($paymentIntent),
        ]);*/
        // Create PSP transaction ledger entry
        PspTransaction::create([
            'transaction_id' => $transaction->Transaction_ID, // or $transaction->Transaction_ID depending on your model PK
            'psp_vendor_id'  => self::STRIPE_PSP_ID,
            'psp_intent_id'  => $paymentIntent->id,
            'psp_charge_id'  => null,
            'amount'         => $amount,   // keep same units as your business decision (recommended: major units DECIMAL, or be consistent)
            'currency'       => strtoupper($currency),
            'status'         => $paymentIntent->status,
            'payload'        => json_encode($paymentIntent),
        ]);
        return [
            'clientSecret' => $paymentIntent->client_secret,
            'paymentIntentId' => $paymentIntent->id,
            'publishableKey'   => env('STRIPE_KEY')
        ];
    }
}
