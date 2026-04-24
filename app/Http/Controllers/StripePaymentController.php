<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\Payments\PaymentInitiationService;
use App\Services\Payments\PaymentUpdateService;
use App\Models\PspTransaction;

/**
 * Class StripePaymentController
 *
 * Handles Stripe payment initiation, transaction updates,
 * status polling, and result pages.
 */
class StripePaymentController extends Controller
{
    /**
     * Inject payment-related services.
     *
     * @param PaymentInitiationService $paymentService
     * @param PaymentUpdateService     $updateService
     */
    public function __construct(
        protected PaymentInitiationService $paymentService,
        protected PaymentUpdateService $updateService
    ) {
        // Explicit assignment for clarity
        $this->paymentService = $paymentService;
        $this->updateService  = $updateService;
    }

    /**
     * Create a Stripe PaymentIntent.
     *
     * This method supports a hybrid flow (Stripe Elements / Checkout).
     * It validates the request, converts amount to cents,
     * and delegates intent creation to the service layer.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPaymentIntent(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'amount'         => 'required|numeric|min:0.5', // Amount in major units
            'currency'       => 'required|string|size:3',   // ISO currency code
            'transaction_id' => 'required|integer',
            // 'flow'        => 'required|in:elements,checkout',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create Stripe PaymentIntent via service
            $result = $this->paymentService->createStripePaymentIntent(
                transactionId: $request->transaction_id,
                amount: (int) round($request->amount * 100), // Convert to smallest currency unit
                currency: $request->currency
                // flow: $request->flow
            );

            return response()->json($result);

        } catch (\Throwable $e) {
            // Log error for debugging
            Log::error('Stripe payment initiation failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Unable to initiate payment'
            ], 500);
        }
    }

    /**
     * Update transaction after client-side validation.
     *
     * Typically called after successful payment confirmation
     * to store customer details against the transaction.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function preValidate(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
            'first_name'        => 'required|string|min:2|max:50',
            'last_name'         => 'required|string|min:2|max:50',
        ]);

        // Return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Update transaction using service layer
            $this->updateService->updateAfterValidation(
                $request->payment_intent_id,
                $request->first_name,
                $request->last_name
            );

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            // Log error for investigation
            Log::error('Update transaction failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Failed to update transaction'
            ], 500);
        }
    }

    /**
     * Payment success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('stripe-checkout.success');
    }

    /**
     * Payment failed page.
     *
     * @return \Illuminate\View\View
     */
    public function failed()
    {
        return view('stripe-checkout.failed');
    }

    /**
     * Payment processing page.
     *
     * @return \Illuminate\View\View
     */
    public function processing()
    {
        return view('stripe-checkout.processing');
    }

    /**
     * Check payment status by Stripe PaymentIntent ID.
     *
     * Used for polling from frontend to determine
     * whether payment is succeeded, failed, or pending.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentStatus(Request $request)
    {
        // Get PaymentIntent ID from query params
        $paymentIntent = $request->query('payment_intent');

        // If no intent provided, treat as pending
        if (!$paymentIntent) {
            return response()->json([
                'status' => 'pending'
            ]);
        }

        // Find transaction by PSP intent ID
        $tx = PspTransaction::where('psp_intent_id', $paymentIntent)->first();

        // If transaction not found, assume pending
        if (!$tx) {
            return response()->json([
                'status' => 'pending'
            ]);
        }

        // Map internal status to frontend-friendly status
        return response()->json([
            'status' => match ($tx->status) {
                'succeeded'           => 'succeeded',
                'failed', 'canceled'  => 'failed',
                default               => 'pending',
            }
        ]);
    }
}
