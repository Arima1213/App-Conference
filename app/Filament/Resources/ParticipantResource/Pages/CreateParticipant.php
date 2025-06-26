<?php

namespace App\Filament\Resources\ParticipantResource\Pages;

use App\Filament\Resources\ParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;

    // jangan tampilkan breadcrumbs pada halaman create
    protected static bool $shouldShowBreadcrumbs = false;

    public function getTitle(): string
    {
        $request = request();
        $conferenceId = null;
        $conferenceName = 'Register Conference';

        if ($request->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->get('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        if ($conferenceId) {
            // Misal model Conference ada di App\Models\Conference
            $conference = \App\Models\Conference::find($conferenceId);
            if ($conference) {
                $conferenceName = $conference->name;
            }
        }

        return 'Register Conference' . ($conferenceName ? ' - ' . $conferenceName : '');
    }
}