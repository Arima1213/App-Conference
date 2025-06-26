<?php

namespace App\Filament\Resources\EducationalInstitutionResource\Pages;

use App\Filament\Resources\EducationalInstitutionResource;
use App\Imports\EducationalInstitutionImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
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
            CreateAction::make(),
            Action::make('importExcel')
                ->label('Import from Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Select Excel File')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data) {
                    Excel::import(new EducationalInstitutionImport, $data['file']);

                    Notification::make()
                        ->title('Import Completed')
                        ->body('The educational institutions have been successfully imported.')
                        ->success()
                        ->send();
                })
                ->modalHeading('Import Educational Institutions')
                ->modalSubmitActionLabel('Import')
                ->modalCancelActionLabel('Cancel'),
            Action::make('viewTemplate')
                ->label('View Excel Template')
                ->icon('heroicon-o-document-text')
                ->modalHeading('Excel Template Format')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalWidth('lg')
                ->modalContent(fn() => view('filament.modals.excel-template')),
        ];
    }
}
