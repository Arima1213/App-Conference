<?php

namespace App\Filament\Resources\ConferenceResource\Pages;

use App\Filament\Resources\ConferenceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateConference extends CreateRecord
{
    protected static string $resource = ConferenceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // bisa manipulasi $data jika perlu sebelum simpan
        return $data;
    }

    protected function afterCreate(): void
    {
        // misalnya log atau notifikasi
        Notification::make()->title('Conference created')->send();
    }
}