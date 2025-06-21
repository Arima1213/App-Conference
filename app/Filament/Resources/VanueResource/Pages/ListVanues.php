<?php

namespace App\Filament\Resources\VanueResource\Pages;

use App\Filament\Resources\VanueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVanues extends ListRecords
{
    protected static string $resource = VanueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
