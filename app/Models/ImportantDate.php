<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    protected $fillable = [
        'conference_id',
        'title',
        'date',
        'description',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}