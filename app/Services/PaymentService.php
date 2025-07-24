<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Participant;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Get or create payment for participant
     *
     * @param int $participantId
     * @return Payment
     */
    public function getOrCreatePayment(int $participantId): Payment
    {
        $participant = Participant::findOrFail($participantId);

        // Check if participant already has a pending payment
        $existingPayment = Payment::where('participant_id', $participantId)
            ->whereIn('payment_status', ['pending', 'failed', 'expired'])
            ->first();

        if ($existingPayment) {
            Log::info("Found existing payment for participant {$participantId}: {$existingPayment->invoice_code}");
            return $existingPayment;
        }

        // Create new payment if no pending payment exists
        $payment = $this->createNewPayment($participant);
        Log::info("Created new payment for participant {$participantId}: {$payment->invoice_code}");

        return $payment;
    }

    /**
     * Create new payment for participant
     *
     * @param Participant $participant
     * @return Payment
     */
    private function createNewPayment(Participant $participant): Payment
    {
        // Get seminar fee for the conference
        $seminarFee = $participant->conference->seminarFees()
            ->where('membership_id', $participant->membership_id)
            ->first();

        if (!$seminarFee) {
            throw new \Exception('Seminar fee not found for this participant membership type.');
        }

        // Generate unique invoice code
        $invoiceCode = $this->generateInvoiceCode($participant);

        return Payment::create([
            'seminar_fee_id' => $seminarFee->id,
            'participant_id' => $participant->id,
            'invoice_code' => $invoiceCode,
            'amount' => $seminarFee->fee,
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Generate unique invoice code
     *
     * @param Participant $participant
     * @return string
     */
    private function generateInvoiceCode(Participant $participant): string
    {
        $prefix = 'INV';
        $conferenceCode = strtoupper(substr($participant->conference->title, 0, 3));
        $timestamp = now()->format('YmdHis');
        $participantId = str_pad($participant->id, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$conferenceCode}-{$timestamp}-{$participantId}";
    }

    /**
     * Get snap token for payment
     *
     * @param Payment $payment
     * @return string
     */
    public function getSnapToken(Payment $payment): string
    {
        // Check if payment can be paid
        if (!$payment->canBePaid()) {
            throw new \Exception('Payment cannot be processed. Current status: ' . $payment->payment_status);
        }

        // Check if existing snap token is still valid
        if ($payment->hasValidSnapToken()) {
            Log::info("Using existing valid snap token for payment {$payment->invoice_code}");
            return $payment->snap_token;
        }

        // Generate new snap token
        Log::info("Generating new snap token for payment {$payment->invoice_code}");
        return $payment->generateSnapToken();
    }

    /**
     * Process payment callback from Midtrans
     *
     * @param array $callbackData
     * @return Payment|null
     */
    public function processCallback(array $callbackData): ?Payment
    {
        $orderId = $callbackData['order_id'] ?? null;
        $transactionStatus = $callbackData['transaction_status'] ?? null;
        $fraudStatus = $callbackData['fraud_status'] ?? null;
        $paymentType = $callbackData['payment_type'] ?? null;

        if (!$orderId) {
            Log::error('Missing order_id in callback data');
            return null;
        }

        $payment = Payment::where('invoice_code', $orderId)->first();

        if (!$payment) {
            Log::error("Payment not found for order_id: {$orderId}");
            return null;
        }

        // Update payment status based on transaction status
        $this->updatePaymentStatus($payment, $transactionStatus, $fraudStatus, $paymentType, $callbackData);

        return $payment;
    }

    /**
     * Update payment status based on Midtrans callback
     *
     * @param Payment $payment
     * @param string|null $transactionStatus
     * @param string|null $fraudStatus
     * @param string|null $paymentType
     * @param array $callbackData
     */
    private function updatePaymentStatus(Payment $payment, ?string $transactionStatus, ?string $fraudStatus, ?string $paymentType, array $callbackData): void
    {
        $oldStatus = $payment->payment_status;

        switch ($transactionStatus) {
            case 'capture':
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        $payment->payment_status = 'challenge';
                    } else {
                        $payment->payment_status = 'paid';
                        $payment->paid_at = now();
                        $this->updateParticipantStatus($payment);
                    }
                }
                break;

            case 'settlement':
                $payment->payment_status = 'paid';
                $payment->paid_at = now();
                $this->updateParticipantStatus($payment);
                break;

            case 'pending':
                $payment->payment_status = 'pending';
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $payment->payment_status = 'failed';
                break;

            default:
                $payment->payment_status = 'pending';
        }

        // Update additional payment info
        $payment->payment_method = $paymentType;
        $payment->va_number = $callbackData['va_numbers'][0]['va_number'] ??
            $callbackData['masked_card'] ??
            null;

        $payment->save();

        Log::info("Payment {$payment->invoice_code} status updated from {$oldStatus} to {$payment->payment_status}");
    }

    /**
     * Update participant status when payment is confirmed
     *
     * @param Payment $payment
     */
    private function updateParticipantStatus(Payment $payment): void
    {
        $participant = $payment->participant;
        $participant->status = 'verified';
        $participant->save();

        Log::info("Participant {$participant->id} status updated to verified");
    }
}
