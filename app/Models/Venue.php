<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'conference_id',
        'address',
        'map_url',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}