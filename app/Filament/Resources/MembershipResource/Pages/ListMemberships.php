<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use App\Imports\MembershipImport;
use App\Exports\MembershipExport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListMemberships extends ListRecords
{
    protected static string $resource = MembershipResource::class;

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
                        ->disk('public')
                        ->directory('imports')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('public')->path($data['file']);
                    Excel::import(new MembershipImport, $filePath);

                    Notification::make()
                        ->title('Import Completed')
                        ->body('The memberships have been successfully imported.')
                        ->success()
                        ->send();
                })
                ->modalHeading('Import Memberships')
                ->modalSubmitActionLabel('Import')
                ->modalCancelActionLabel('Cancel'),
            Action::make('exportExcel')
                ->color('danger')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): BinaryFileResponse {
                    $fileName = 'memberships_' . now()->format('Ymd_His') . '.xlsx';
                    return Excel::download(new MembershipExport, $fileName);
                })
                ->requiresConfirmation()
                ->modalHeading('Export Confirmation')
                ->modalDescription('Are you sure you want to export all memberships to Excel?')
                ->modalSubmitActionLabel('Export')
                ->modalCancelActionLabel('Cancel'),
            Action::make('viewTemplate')
                ->color('info')
                ->label('Template')
                ->icon('heroicon-o-document-text')
                ->modalHeading('Excel Template Format')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalWidth('7xl')
                ->modalContent(fn() => view('filament.modals.member-template')),
        ];
    }
}