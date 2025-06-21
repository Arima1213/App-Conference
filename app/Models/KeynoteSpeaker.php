<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeynoteSpeaker extends Model
{
    protected $table = 'keynote_speakers';

    protected $fillable = [
        'name',
        'institution',
        'bio',
        'photo',
    ];
}