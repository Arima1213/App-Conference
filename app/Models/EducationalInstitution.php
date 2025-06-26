<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalInstitution extends Model
{
    protected $table = 'educational_institutions';

    protected $fillable = [
        'lembaga',
        'kelompok_koordinator',
        'npsn',
        'nama_pt',
        'nm_bp',
        'provinsi_pt',
        'jln',
        'kec_pt',
        'kabupaten_kota',
        'website',
        'no_tel',
        'email',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}