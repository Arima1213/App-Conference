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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function seminarFee()
    {
        return $this->belongsTo(SeminarFee::class);
    }
}