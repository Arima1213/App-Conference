<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = [
        'title',
        'description',
        'schedule_id',
        'venue_id',
        'banner',
        'is_active',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function venue()
    {
        return $this->belongsTo(venue::class);
    }
}
