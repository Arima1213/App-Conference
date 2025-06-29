<?php

namespace App\Exports;

use App\Models\Membership;
use Maatwebsite\Excel\Concerns\FromCollection;

class MembershipExport implements FromCollection
{
    public function collection()
    {
        return Membership::select('nama_lengkap', 'no_hp', 'no_anggota')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'No HP',
            'No Anggota',
        ];
    }
}