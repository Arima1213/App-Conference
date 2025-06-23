<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'conference_id',
        'nik',
        'university',
        'phone',
        'participant_code',
        'paper_title',
        'qrcode',
        'status',
        'seminar_kit_status',
    ];

    protected $casts = [
        'seminar_kit_status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
