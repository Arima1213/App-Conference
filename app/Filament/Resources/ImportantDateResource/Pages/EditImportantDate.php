<?php

namespace App\Filament\Resources\ImportantDateResource\Pages;

use App\Filament\Resources\ImportantDateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportantDate extends EditRecord
{
    protected static string $resource = ImportantDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
