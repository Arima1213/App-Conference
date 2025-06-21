<?php

namespace App\Filament\Resources\SeminarFeeResource\Pages;

use App\Filament\Resources\SeminarFeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeminarFee extends EditRecord
{
    protected static string $resource = SeminarFeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
