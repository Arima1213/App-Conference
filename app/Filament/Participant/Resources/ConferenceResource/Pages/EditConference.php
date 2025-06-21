<?php

namespace App\Filament\Participant\Resources\ConferenceResource\Pages;

use App\Filament\Participant\Resources\ConferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConference extends EditRecord
{
    protected static string $resource = ConferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
