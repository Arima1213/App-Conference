<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarFee extends Model
{
    protected $fillable = [
        'conference_id',
        'type',
        'category',
        'early_bird_price',
        'regular_price',
        'currency',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}