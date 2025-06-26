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
            'lembaga'              => $row[0] ?? null,
            'kelompok_koordinator' => $row[1] ?? null,
            'npsn'                 => $row[2] ?? null,
            'nama_pt'              => $row[3] ?? null,
            'nm_bp'                => $row[4] ?? null,
            'provinsi_pt'          => $row[5] ?? null,
            'jln'                  => $row[6] ?? null,
            'kec_pt'               => $row[7] ?? null,
            'kabupaten_kota'       => $row[8] ?? null,
            'website'              => $row[9] ?? null,
            'no_tel'               => $row[10] ?? null,
            'email'                => $row[11] ?? null,
        ]);
    }
}