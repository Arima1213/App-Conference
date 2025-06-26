<?php

namespace App\Filament\Resources\EducationalInstitutionResource\Pages;

use App\Filament\Resources\EducationalInstitutionResource;
use App\Imports\EducationalInstitutionImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Form;
use Filament\Notifications\Notification;

class ListEducationalInstitutions extends ListRecords
{
    protected static string $resource = EducationalInstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Import Excel')
                ->form([
                    FileUpload::make('file')
                        ->label('Excel File')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']),
                ])
                ->action(function (array $data) {
                    Excel::import(new EducationalInstitutionImport, $data['file']);

                    Notification::make()
                        ->title('Import successful')
                        ->success()
                        ->send();
                })
                ->modalHeading('Import Educational Institutions')
                ->modalSubmitActionLabel('Import'),
        ];
    }
}
