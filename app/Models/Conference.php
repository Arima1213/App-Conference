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

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function seminarFees()
    {
        return $this->hasMany(SeminarFee::class);
    }

    public function importantDates()
    {
        return $this->hasMany(ImportantDate::class);
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}