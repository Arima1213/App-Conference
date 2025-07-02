<?php

namespace App\Imports;

use App\Models\Membership;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Events\AfterImport;


class MembershipImport implements ToModel, WithChunkReading, ShouldQueue
{
    public function model(array $row)
    {
        if ($row[0] === 'nama_lengkap') {
            return null;
        }

        return new Membership([
            'nama_lengkap' => $row[0] ?? null,
            'no_hp' => $row[1] ?? null,
            'no_anggota' => $row[2] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 100; // Bisa disesuaikan
    }


    public static function afterImport(AfterImport $event)
    {
        Notification::make()
            ->title('Import Selesai')
            ->body('Import membership telah selesai diproses.')
            ->success()
            ->send();
    }
}
