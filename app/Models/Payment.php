<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'seminar_fee_id',
        'participant_id',
        'invoice_code',
        'amount',
        'paid_at',
        'payment_status',
        'payment_method',
        'va_number',
        'snap_token',
        'snap_token_created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'snap_token_created_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function seminarFee()
    {
        return $this->belongsTo(SeminarFee::class);
    }

    /**
     * Check if payment has a valid snap token
     * Snap token expires after 24 hours
     */
    public function hasValidSnapToken(): bool
    {
        if (!$this->snap_token || !$this->snap_token_created_at) {
            return false;
        }

        // Token expires after 24 hours
        $expiryTime = $this->snap_token_created_at->addHours(24);
        return now()->lt($expiryTime);
    }

    /**
     * Check if payment can be paid
     */
    public function canBePaid(): bool
    {
        return in_array($this->payment_status, ['pending', 'failed', 'expired']);
    }

    /**
     * Check if payment is completed
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Generate new snap token for payment
     */
    public function generateSnapToken(): string
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = env('APP_ENV') === 'production';
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $this->invoice_code,
                'gross_amount' => $this->amount,
            ],
            'customer_details' => [
                'first_name' => $this->participant->user->name ?? '',
                'email' => $this->participant->user->email ?? '',
                'phone' => $this->participant->phone ?? '',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Update payment with new snap token
        $this->update([
            'snap_token' => $snapToken,
            'snap_token_created_at' => now(),
        ]);

        return $snapToken;
    }
}
