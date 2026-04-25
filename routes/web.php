<?php
use App\Http\Controllers\StripePaymentController;
use Illuminate\Http\Request;

Route::get('/checkout/{id}', function ($id) {
    return view('checkout.index', compact('id'));
})->name('checkout');

Route::get('/', function () {
    return view('home.index');
})->name('home');;

Route::prefix('stripe-checkout')
    ->name('stripe-checkout.')
    ->group(function () {

        Route::post('/pay/intent', [StripePaymentController::class, 'createPaymentIntent'])
            ->name('create-intent');

        Route::post('/pre-validate', [StripePaymentController::class, 'preValidate'])
            ->name('pre-validate');

        Route::get('/processing', [StripePaymentController::class, 'processing'])
            ->name('processing');

        Route::get('/success', [StripePaymentController::class, 'success'])
            ->name('success');

        Route::get('/failed', [StripePaymentController::class, 'failed'])
            ->name('failed');

        Route::get('/pending', function () {
            return view('payments.pending');
        })->name('pending');

        Route::get('/status', [StripePaymentController::class, 'paymentStatus'])
            ->name('status');
    });