<?php

namespace App\Services\Payments;

use App\Models\PspTransaction;
use App\Models\TransactionBookingResale;
use Illuminate\Support\Facades\Log;
use App\Enums\PaymentStatus; 
class PaymentReconciliationService
{
    public function reconcileStripePayment(
        string $stripePaymentIntentId,
        int $paymentStatusId,
        array $rawPayload
    ): void {
        // Find PSP transaction
        $pspTransaction = PspTransaction::where(
            'psp_intent_id',
            $stripePaymentIntentId
        )->first();

        if (!$pspTransaction) {
            Log::warning('PSP transaction not found', [
                'stripe_payment_intent_id' => $stripePaymentIntentId,
            ]);
            return;
        }

        // Update PSP transaction (idempotent)
        $pspTransaction->update([
            'status'        => $rawPayload['data']['object']['status'] ?? $pspTransaction->status,
            'psp_charge_id' => $rawPayload['data']['object']['latest_charge']
                ?? $pspTransaction->psp_charge_id,
            'payload'       => json_encode($rawPayload),
        ]);
        Log::warning('pspTransaction table update', [
                'status' => $rawPayload['data']['object']['status'] ?? $pspTransaction->status,
            ]);
        
        // Resolve business transaction
        $transaction = TransactionBookingResale::find(
            $pspTransaction->transaction_id
        );

        if (!$transaction) {
            Log::notice('Business transaction not found yet', [
                'psp_transaction_id' => $pspTransaction->id,
            ]);
            return;
        }

        /*// Idempotency check
        if ($transaction->payment_status_id === $paymentStatusId) {
            return;
        }*/

        if (
            $transaction->payment_status_id === PaymentStatus::COMPLETED->value &&
            $paymentStatusId !== PaymentStatus::COMPLETED->value
        ) {
            return;
        }

        $transaction->update([
            'payment_status_id' => $paymentStatusId,
        ]);
    }
}
