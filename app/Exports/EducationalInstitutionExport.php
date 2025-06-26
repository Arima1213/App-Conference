<?php

namespace App\Exports;

use App\Models\EducationalInstitution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EducationalInstitutionExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return EducationalInstitution::select(
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
            'email'
        )->get();
    }

    public function headings(): array
    {
        return [
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
    }
}