<?php

namespace App\Imports;

use App\Models\EducationalInstitution;
use Maatwebsite\Excel\Concerns\ToModel;

class EducationalInstitutionImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EducationalInstitution([
            'lembaga'              => $row['lembaga'] ?? null,
            'kelompok_koordinator' => $row['kelompok_koordinator'] ?? null,
            'npsn'                 => $row['npsn'] ?? null,
            'nama_pt'              => $row['nama_pt'] ?? null,
            'nm_bp'                => $row['nm_bp'] ?? null,
            'provinsi_pt'          => $row['provinsi_pt'] ?? null,
            'jln'                  => $row['jln'] ?? null,
            'kec_pt'               => $row['kec_pt'] ?? null,
            'kabupaten_kota'       => $row['kabupaten_kota'] ?? null,
            'website'              => $row['website'] ?? null,
            'no_tel'               => $row['no_tel'] ?? null,
            'email'                => $row['email'] ?? null,
        ]);
    }
}
