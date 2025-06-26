<?php

namespace App\Filament\Participant\Resources\ParticipantResource\Pages;

use App\Filament\Participant\Resources\ParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function getFormActions(): array
    {
        // Override to return an empty array, removing all form actions including the 'Create' button
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return ParticipantResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Participant successfully registered!';
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

    // sebelum menjalankan create, lengkapi form participant code
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil conference_id dari query string
        $request = request();
        $conferenceId = null;

        if ($request->query->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->query('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        $fee = '';
        if ($conferenceId) {
            $conference = \App\Models\Conference::find($conferenceId);
            if ($conference && isset($conference->fee)) {
                $fee = (string) $conference->fee;
            }
        }

        // Gunakan fee sebagai bagian dari pattern participant_code
        $participantCode = 'P' . $fee . strtoupper(uniqid());
        // dd($participantCode); // Uncomment if you want to debug

        $data['participant_code'] = $participantCode;

        return $data;
    }
}