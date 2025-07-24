<?php

namespace App\Filament\Participant\Pages;

use App\Models\Payment;
use App\Services\PaymentService;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentPage extends Page
{
    protected static string $view = 'filament.pages.payment-page';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $title = 'Payment Page';
    // sembunyikan dari nav
    protected static bool $shouldRegisterNavigation = false;

    public $snapToken;
    public $payment;

    public function mount(Request $request)
    {
        try {
            $encryptedPaymentId = $request->input('payment');
            $paymentId = decrypt($encryptedPaymentId);

            // Ambil data payment dari database
            $payment = Payment::findOrFail($paymentId);

            // Initialize PaymentService
            $paymentService = app(PaymentService::class);

            // Get snap token using service (handles existing token validation)
            $this->snapToken = $paymentService->getSnapToken($payment);
            $this->payment = $payment;

            Log::info("Payment page mounted for payment {$payment->invoice_code}");
        } catch (\Exception $e) {
            Log::error('Payment page mount error: ' . $e->getMessage());
            abort(404, 'Payment not found or cannot be processed: ' . $e->getMessage());
        }
    }
}
