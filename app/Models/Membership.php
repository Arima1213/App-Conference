<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'nama_lengkap',
        'no_hp',
        'no_anggota',
    ];
}