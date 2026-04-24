<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\Transaction;
use App\Models\PaymentMethod as StripePaymentMethod;

class PaymentController extends Controller
{
    /**
     * Constructor → Stripe Secret Key Set Once
     */
    public function __construct()
    {
        // Set Stripe API key once for entire controller
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show Stripe Checkout Page (Frontend)
     */
    public function showPaymentPage()
    {
        return view('payment.checkout');
    }

    // ======================================================================
    // 1️⃣ CREATE PAYMENT INTENT (Called when page loads)
    // ======================================================================
    public function createPaymentIntent(Request $request)
    {
        try {
            // Amount in minor units (₹500 => 50000 paise)
            $amount = 50000;

            // Create temporary Stripe customer
            $customer = Customer::create();

            // Fetch enabled payment method types from database
            $enabledMethods = StripePaymentMethod::where('enabled', true)
                ->pluck('name')
                ->toArray();

            if (empty($enabledMethods)) {
                return response()->json(['error' => 'No payment methods enabled'], 422);
            }

            // Create PaymentIntent on Stripe
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'customer' => $customer->id,
                'payment_method_types' => $enabledMethods,
            ]);

            // Store transaction locally
            Transaction::create([
                'stripe_payment_intent_id' => $intent->id,
                'stripe_customer_id' => $customer->id,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            return response()->json([
                'clientSecret'     => $intent->client_secret,
                'paymentIntentId'  => $intent->id,
                'publishableKey'   => env('STRIPE_KEY')
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Create PaymentIntent Error: '.$e->getMessage());
            return response()->json(['error' => 'Unable to create payment intent'], 500);
        }
    }

    // ======================================================================
    // 2️⃣ UPDATE TRANSACTION AFTER PAYMENT CONFIRMATION (AJAX)
    // ======================================================================
    public function updateTransaction(Request $request)
    {
        // Validate required fields from frontend
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
            'first_name'        => 'required|string|min:6|max:50|regex:/^[A-Za-z\s\-]+$/',
            'last_name'         => 'required|string|min:6|max:50|regex:/^[A-Za-z\s\-]+$/',
        ]);

        // Field-wise error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        /*if ($request->has('test_only')) {
            return response()->json(['success' => true]);
        }*/

        $paymentIntentId = $request->payment_intent_id;

        try {
            // Retrieve PaymentIntent from Stripe
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if (!$paymentIntent) {
                return response()->json(['error' => 'PaymentIntent not found'], 404);
            }

            // Get payment method details (billing info)
            $paymentMethod = $paymentIntent->payment_method
                ? PaymentMethod::retrieve($paymentIntent->payment_method)
                : null;

            $billing = $paymentMethod->billing_details ?? (object)[];
            $address = $billing->address ?? (object)[];

            // Extract billing full name -> first + last
            $fullName = trim($billing->name ?? '');
            $parts = preg_split('/\s+/', $fullName);

            $billingFirst = htmlspecialchars($parts[0] ?? '', ENT_QUOTES, 'UTF-8');
            $billingLast  = isset($parts[1])
                ? htmlspecialchars(implode(' ', array_slice($parts, 1)), ENT_QUOTES, 'UTF-8')
                : '';

            // Sanitize user-input first & last name
            $firstName = preg_replace("/[^a-zA-Z\s\-']/u", "", trim(strip_tags($request->first_name)));
            $lastName  = preg_replace("/[^a-zA-Z\s\-']/u", "", trim(strip_tags($request->last_name)));

            // Update Stripe customer record
            if ($paymentIntent->customer) {
                Customer::update(
                    $paymentIntent->customer,
                    array_filter([
                        'name'  => $billing->name ?? null,
                        'email' => $billing->email ?? null,
                        'phone' => $billing->phone ?? null,
                    ])
                );
            }

            // Update local transaction entry
            Transaction::where('stripe_payment_intent_id', $paymentIntentId)->update([
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'email'             => filter_var($billing->email ?? '', FILTER_SANITIZE_EMAIL),
                'contact_number'    => htmlspecialchars($billing->phone ?? '', ENT_QUOTES, 'UTF-8'),

                'billing_first_name'=> $billingFirst,
                'billing_last_name' => $billingLast,
                'billing_country'   => htmlspecialchars($address->country ?? '', ENT_QUOTES, 'UTF-8'),
                'billing_street'    => htmlspecialchars($address->line1 ?? '', ENT_QUOTES, 'UTF-8'),
                'billing_city'      => htmlspecialchars($address->city ?? '', ENT_QUOTES, 'UTF-8'),
                'billing_state'     => htmlspecialchars($address->state ?? '', ENT_QUOTES, 'UTF-8'),
                'billing_zip'       => htmlspecialchars($address->postal_code ?? '', ENT_QUOTES, 'UTF-8'),

                'status' => $paymentIntent->status,
                'metadata' => json_encode($paymentIntent->metadata ?? []),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Stripe Update Transaction Error: '.$e->getMessage());
            return response()->json(['error' => 'Failed to update transaction'], 500);
        }
    }

    // ======================================================================
    // 3️⃣ PAYMENT SUCCESS PAGE (Redirect After Stripe)
    // ======================================================================
    public function paymentSuccess(Request $request)
    {
        $paymentIntentId = $request->get("payment_intent");

        if (!$paymentIntentId) {
            return redirect('/pay')->with('error', 'PaymentIntent ID missing');
        }

        try {
            // Retrieve PaymentIntent details
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Fallback to ensure transaction is updated
            $this->updateTransaction(new Request([
                'payment_intent_id' => $paymentIntentId,
                'first_name'        => '',
                'last_name'         => ''
            ]));

            return view('payment.success', compact('paymentIntent'));

        } catch (\Exception $e) {
            Log::error('Stripe Payment Success Error: '.$e->getMessage());
            return redirect('/pay')->with('error', 'Unable to retrieve payment information');
        }
    }

    // ======================================================================
    // 4️⃣ PAYMENT FAILED PAGE
    // ======================================================================
    public function paymentFailed()
    {
        return view('payment.fail');
    }
}
