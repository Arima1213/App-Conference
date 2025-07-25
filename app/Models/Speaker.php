<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'name',
        'position',
        'bio',
        'photo',
        'is_keynote',
    ];
}