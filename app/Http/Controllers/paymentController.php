<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Payment;
use App\Services\PaymentService;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class paymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Process payment - improved version to handle existing transactions
     */
    public function pay(Request $request)
    {
        try {
            $encryptedPaymentId = $request->input('payment');
            $paymentId = decrypt($encryptedPaymentId);
            $payment = Payment::findOrFail($paymentId);

            // Get snap token using service (handles existing token validation)
            $snapToken = $this->paymentService->getSnapToken($payment);

            return view('payment.pay', [
                'snapToken' => $snapToken,
                'payment' => $payment,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans callback - improved version with better validation and logging
     */
    public function callback(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $orderId = $request->input('order_id');
            $statusCode = $request->input('status_code');
            $grossAmount = $request->input('gross_amount');
            $requestSignature = $request->input('signature_key');

            // Validate required fields
            if (!$orderId || !$statusCode || !$grossAmount || !$requestSignature) {
                Log::error('Missing required parameters in Midtrans callback', $request->all());
                return response()->json(['message' => 'Missing required parameters'], 400);
            }

            // Generate and verify signature
            $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signatureKey !== $requestSignature) {
                Log::error('Invalid signature in Midtrans callback', [
                    'order_id' => $orderId,
                    'expected_signature' => $signatureKey,
                    'received_signature' => $requestSignature
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Process callback using service
            $callbackData = $request->all();
            $payment = $this->paymentService->processCallback($callbackData);

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Send notifications based on payment status
            $this->sendPaymentNotification($payment);

            Log::info("Midtrans callback processed successfully for payment {$payment->invoice_code}");

            return response()->json([
                'message' => 'Callback processed successfully',
                'status' => $payment->payment_status,
                'order_id' => $payment->invoice_code
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans callback processing error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Send notification to user based on payment status
     */
    private function sendPaymentNotification(Payment $payment): void
    {
        $user = $payment->participant->user;

        switch ($payment->payment_status) {
            case 'paid':
                Notification::make()
                    ->title('Payment Successful')
                    ->body('Thank you! Your payment has been confirmed. You can now access all conference features.')
                    ->success()
                    ->sendToDatabase($user);

                // Send email notification
                try {
                    Mail::to($user->email)->send(new PaymentSuccessMail($payment));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email: ' . $e->getMessage());
                }
                break;

            case 'challenge':
                Notification::make()
                    ->title('Payment Under Verification')
                    ->body('Your payment is under verification by the bank. We will notify you once it is confirmed.')
                    ->warning()
                    ->sendToDatabase($user);
                break;

            case 'failed':
                Notification::make()
                    ->title('Payment Failed')
                    ->body('Your payment has failed. Please try again or contact our support team.')
                    ->danger()
                    ->sendToDatabase($user);
                break;

            case 'expired':
                Notification::make()
                    ->title('Payment Expired')
                    ->body('Your payment session has expired. Please initiate a new payment.')
                    ->warning()
                    ->sendToDatabase($user);
                break;
        }
    }
}
