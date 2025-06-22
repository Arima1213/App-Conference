<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'conference_id',
        'name',
        'logo',
        'website',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}