<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = [
        'title',
        'description',
        'banner',
        'is_active',
    ];

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function venue()
    {
        return $this->hasMany(Venue::class);
    }
}