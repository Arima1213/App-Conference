<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarFee extends Model
{
    protected $fillable = [
        'conference_id',
        'type',
        'is_member',
        'category',
        'early_bird_price',
        'regular_price',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}