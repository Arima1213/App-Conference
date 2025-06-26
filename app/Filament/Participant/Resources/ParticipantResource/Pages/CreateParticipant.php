<?php

namespace App\Filament\Participant\Resources\ParticipantResource\Pages;

use App\Filament\Participant\Resources\ParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;


    protected function getRedirectUrl(): string
    {
        return ParticipantResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Participant successfully registered!';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
        ];
    }

    protected function hasCreateAnother(): bool
    {
        return false; // hilangkan tombol 'Create & create another'
    }

    // jangan tampilkan breadcrumbs pada halaman create
    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        $request = request();
        $conferenceId = null;
        $conferenceName = '';

        // Ambil parameter dari query string, bukan dari request body
        if ($request->query->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->query('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        if ($conferenceId) {
            // Misal model Conference ada di App\Models\Conference
            $conference = \App\Models\Conference::find($conferenceId);
            if ($conference) {
                $conferenceName = $conference->title;
            }
        }

        return 'Register Conference' . ($conferenceName ? ' - ' . $conferenceName : '');
    }
}