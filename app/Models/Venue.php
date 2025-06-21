<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'address',
        'map_url',
    ];

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }
}