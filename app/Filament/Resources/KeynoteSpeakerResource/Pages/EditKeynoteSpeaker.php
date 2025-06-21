<?php

namespace App\Filament\Resources\KeynoteSpeakerResource\Pages;

use App\Filament\Resources\KeynoteSpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeynoteSpeaker extends EditRecord
{
    protected static string $resource = KeynoteSpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
