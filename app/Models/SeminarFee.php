<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarFee extends Model
{
    protected $fillable = [
        'type',
        'category',
        'early_bird_price',
        'regular_price',
        'currency',
    ];
}