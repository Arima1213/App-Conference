<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'speaker_id',
        'conference_id',
        'start_time',
        'end_time',
        'title',
        'subtitle',
        'description',
    ];

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}