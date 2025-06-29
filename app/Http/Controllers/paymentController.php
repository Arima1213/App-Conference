<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Payment;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class paymentController extends Controller
{
    // fungsi pay
    public function pay(Request $request)
    {
        // Ambil dan dekripsi payment id dari request
        $encryptedPaymentId = $request->input('payment');
        $paymentId = decrypt($encryptedPaymentId);

        // Ambil invoice_code dari payment id
        $payment = \App\Models\Payment::find($paymentId);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = env('APP_ENV') === 'production';
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $payment ? $payment->invoice_code : null,
                'gross_amount' => $payment ? $payment->amount : null,
            ],
            'customer_details' => [
                'first_name' => $payment ? $payment->participant->user->name : '',
                'email' => $payment ? $payment->participant->user->email : '',
                'phone' => $payment ? $payment->participant->phone : '',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return view('payment.pay', [
            'snapToken' => $snapToken,
            'payment' => $payment,
        ]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $requestSignature = $request->input('signature_key');

        // Validate required fields
        if (!$orderId || !$statusCode || !$grossAmount || !$requestSignature) {
            return response()->json(['message' => 'Missing required parameters'], 400);
        }

        // Generate signature
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Verify signature
        if ($signatureKey !== $requestSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find payment by order_id (invoice_code)
        $payment = Payment::where('invoice_code', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Handle different transaction statuses
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        switch ($transactionStatus) {
            case 'capture':
                // Jika pembayaran menggunakan kartu kredit, cek status fraud
                if ($request->input('payment_type') == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        // Pembayaran perlu verifikasi lebih lanjut oleh bank (fraud challenge)
                        $payment->payment_status = 'pending'; // status tetap pending sampai verifikasi selesai
                    } else {
                        // Pembayaran berhasil dan sudah diverifikasi
                        $payment->payment_status = 'paid';
                        $payment->paid_at = now();
                        // Update status peserta menjadi 'verified'
                        $payment->participant->status = 'verified';
                        $payment->participant->save();
                    }
                }
                break;
            case 'settlement':
                // Pembayaran berhasil (umumnya untuk transfer bank, e-wallet, dll)
                $payment->payment_status = 'paid';
                $payment->paid_at = now();
                // Update status peserta menjadi 'verified'
                $payment->participant->status = 'verified';
                $payment->participant->save();
                break;
            case 'pending':
                // Pembayaran masih menunggu (belum dibayar atau menunggu konfirmasi)
                $payment->payment_status = 'pending';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                // Pembayaran gagal (ditolak, kadaluarsa, atau dibatalkan)
                $payment->payment_status = 'failed';
                break;
            default:
                // Status tidak diketahui, set sebagai pending untuk keamanan
                $payment->payment_status = 'pending';
        }

        // Update additional fields
        $payment->payment_method = $request->input('payment_type') ?? null;
        $payment->va_number = $request->input('va_numbers.0.va_number') ?? $request->input('masked_card') ?? null;
        $payment->save();

        // Send notification only if paid
        if ($payment->payment_status === 'paid') {
            Notification::make()
                ->title('Payment Successful')
                ->body('Thank you, your payment has been confirmed.')
                ->success()
                ->sendToDatabase($payment->participant->user);
        } elseif ($payment->payment_status === 'challenge') {
            Notification::make()
                ->title('Payment Under Verification')
                ->body('Your payment is under verification by the bank.')
                ->warning()
                ->sendToDatabase($payment->participant->user);
        } elseif (in_array($payment->payment_status, ['deny', 'expire', 'cancel'])) {
            Notification::make()
                ->title('Payment Failed')
                ->body('Your payment has failed. Please try again or contact the administrator.')
                ->danger()
                ->sendToDatabase($payment->participant->user);
        }
        return response()->json(['message' => 'Callback processed', 'status' => $payment->payment_status]);
        return redirect()->url('/participant');
    }
}