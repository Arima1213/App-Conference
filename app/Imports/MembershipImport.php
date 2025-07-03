<?php

namespace App\Imports;

use App\Models\Membership;
use Maatwebsite\Excel\Concerns\ToModel;

class MembershipImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row
        if ($row[0] === 'nama_lengkap') {
            return null;
        }
        return new Membership([
            'nama_lengkap' => $row[0] ?? null,
            'no_hp' => $row[1] ?? null,
            'no_anggota' => $row[2] ?? null,
        ]);
    }
}
