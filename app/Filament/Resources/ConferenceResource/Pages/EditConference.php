<?php

namespace App\Filament\Resources\ConferenceResource\Pages;

use App\Filament\Resources\ConferenceResource;
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

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index'); // Redirect ke index setelah submit
    }
    protected function hasCreateAnother(): bool
    {
        return false; // hilangkan tombol 'Create & create another'
    }
    protected function getFormActions(): array
    {
        return []; // hilangkan tombol create bawaan

    }
}
