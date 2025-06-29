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
use App\Exports\EducationalInstitutionExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Filament\Notifications\Notification;

class ListEducationalInstitutions extends ListRecords
{
    protected static string $resource = EducationalInstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('importExcel')
                ->color('success')
                ->label('Import')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Select Excel File')
                        ->disk('public') // pastikan file disimpan ke disk yang benar
                        ->directory('imports') // opsional: folder khusus untuk upload excel
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('public')->path($data['file']);

                    Excel::import(new EducationalInstitutionImport, $filePath);

                    Notification::make()
                        ->title('Import Completed')
                        ->body('The educational institutions have been successfully imported.')
                        ->success()
                        ->send();
                })
                ->modalHeading('Import Educational Institutions')
                ->modalSubmitActionLabel('Import')
                ->modalCancelActionLabel('Cancel'),
            Action::make('exportExcel')
                ->color('danger')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): BinaryFileResponse {
                    $fileName = 'educational_institutions_' . now()->format('Ymd_His') . '.xlsx';
                    return Excel::download(new EducationalInstitutionExport, $fileName);
                })
                ->requiresConfirmation()
                ->modalHeading('Export Confirmation')
                ->modalDescription('Are you sure you want to export all educational institutions to Excel?')
                ->modalSubmitActionLabel('Export')
                ->modalCancelActionLabel('Cancel'),
            Action::make('viewTemplate')
                ->color('info')
                ->label('Template')
                ->icon('heroicon-o-document-text')
                ->modalHeading('Excel Template Format')
                ->modalSubmitAction(false)
                ->modalCancelAction(false) // menghilangkan tombol Close, hanya menyisakan icon X
                ->modalWidth('7xl')
                ->modalContent(fn() => view('filament.modals.educational-template')),
        ];
    }
}