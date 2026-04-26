<?php

use Illuminate\Http\Request;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file defines all web routes for the application.
| Each route is mapped to a controller method or closure.
*/

/**
 * Home Page Route
 * Displays the main landing page.
 */
Route::get('/', function () {
    return view('home.index');
})->name('home');


/**
 * Checkout Route
 * Loads the checkout page using a unique UUID.
 */
Route::get('/checkout/{uuid}', [CheckoutController::class, 'checkout'])
    ->name('checkout');


/**
 * Stripe Checkout Routes Group
 * Handles all payment-related operations using Stripe.
 */
Route::prefix('stripe-checkout')
    ->name('stripe-checkout.')
    ->group(function () {

        /**
         * Create Payment Intent
         * Initializes a Stripe payment intent.
         */
        Route::post('/pay/intent', [StripePaymentController::class, 'createPaymentIntent'])
            ->name('create-intent');

        /**
         * Pre-validation Route
         * Validates request data before creating a payment.
         */
        Route::post('/pre-validate', [StripePaymentController::class, 'preValidate'])
            ->name('pre-validate');

        /**
         * Processing Page
         * Displays the payment processing screen.
         */
        Route::get('/processing', [StripePaymentController::class, 'processing'])
            ->name('processing');

        /**
         * Success Page
         * Displays after successful payment completion.
         */
        Route::get('/success', [StripePaymentController::class, 'success'])
            ->name('success');

        /**
         * Failed Page
         * Displays when a payment fails.
         */
        Route::get('/failed', [StripePaymentController::class, 'failed'])
            ->name('failed');

        /**
         * Pending Page
         * Displays when payment status is pending.
         */
        Route::get('/pending', function () {
            return view('payments.pending');
        })->name('pending');

        /**
         * Payment Status Endpoint
         * Returns the current status of the payment.
         */
        Route::get('/status', [StripePaymentController::class, 'paymentStatus'])
            ->name('status');
    });