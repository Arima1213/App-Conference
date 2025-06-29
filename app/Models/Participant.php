<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'conference_id',
        'seminar_fee_id',
        'nik',
        'educational_institution_id',
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

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function educationalInstitution()
    {
        return $this->belongsTo(EducationalInstitution::class);
    }

    public function seminarFee()
    {
        return $this->belongsTo(SeminarFee::class);
    }
}