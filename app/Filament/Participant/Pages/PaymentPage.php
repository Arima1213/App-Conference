<?php

namespace App\Filament\Participant\Pages;

use App\Models\Payment;
use Filament\Pages\Page;
use Illuminate\Http\Request;

class PaymentPage extends Page
{
    protected static string $view = 'filament.pages.payment-page';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $title = 'Payment Page';

    public $snapToken;
    public $payment;

    public function mount(Request $request)
    {
        $encryptedPaymentId = $request->input('payment');
        $paymentId = decrypt($encryptedPaymentId);

        // Ambil data payment dari database
        $payment = Payment::find($paymentId);

        if (!$payment) {
            abort(404, 'Payment not found.');
        }

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = env('APP_ENV') === 'production';
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $payment->invoice_code,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $payment->participant->user->name ?? '',
                'email' => $payment->participant->user->email ?? '',
                'phone' => $payment->participant->phone ?? '',
            ],
        ];

        $this->snapToken = \Midtrans\Snap::getSnapToken($params);
        $this->payment = $payment;
    }
}