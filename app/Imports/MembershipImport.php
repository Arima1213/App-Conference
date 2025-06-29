<?php

namespace App\Imports;

use App\Models\Membership;
use Maatwebsite\Excel\Concerns\ToModel;

class MembershipImport implements ToModel
{
    public function model(array $row)
    {
        return new Membership([
            'nama_lengkap' => $row['nama_lengkap'],
            'no_hp' => $row['no_hp'],
            'no_anggota' => $row['no_anggota'],
        ]);
    }
}