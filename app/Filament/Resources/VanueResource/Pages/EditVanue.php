<?php

namespace App\Filament\Resources\VanueResource\Pages;

use App\Filament\Resources\VanueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVanue extends EditRecord
{
    protected static string $resource = VanueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
