<?php

namespace App\Filament\Resources\KeynoteSpeakerResource\Pages;

use App\Filament\Resources\KeynoteSpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeynoteSpeakers extends ListRecords
{
    protected static string $resource = KeynoteSpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
