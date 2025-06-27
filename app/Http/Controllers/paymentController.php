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
        $signatureKey = hash('sha512', $request->input('order_id') .
            $request->input('status_code') .
            $request->input('gross_amount') .
            $serverKey);

        $requestSignature = $request->input('signature_key');

        dd($signatureKey, $requestSignature);


        $data = $request->all();

        // Cari berdasarkan order_id (invoice_code)
        $payment = Payment::where('invoice_code', $data['order_id'])->firstOrFail();

        // Update status
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        // Kirim notifikasi
        Notification::make()
            ->title('Pembayaran berhasil')
            ->body('Terima kasih, pembayaran Anda telah dikonfirmasi.')
            ->success()
            ->sendToDatabase($payment->participant->user);

        // Kirim email
        Mail::to($payment->participant->user->email)->send(new PaymentSuccessMail($payment));

        return response()->json(['message' => 'Pembayaran berhasil diproses']);
    }
}