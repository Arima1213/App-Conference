<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vanue extends Model
{
    protected $table = 'venues';

    protected $fillable = [
        'name',
        'address',
        'map_url',
    ];
}