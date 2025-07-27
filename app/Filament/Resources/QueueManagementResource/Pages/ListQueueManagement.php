<?php

namespace App\Filament\Resources\QueueManagementResource\Pages;

use App\Filament\Resources\QueueManagementResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class ListQueueManagement extends ListRecords
{
    protected static string $resource = QueueManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('restart_queue')
                ->label('Restart Queue Workers')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    Artisan::call('queue:restart');

                    Notification::make()
                        ->title('Queue Restarted')
                        ->body('All queue workers have been signaled to restart gracefully')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('monitor_health')
                ->label('Check Queue Health')
                ->icon('heroicon-o-heart')
                ->color('info')
                ->action(function () {
                    Artisan::call('queue:monitor', ['--check' => true]);

                    Notification::make()
                        ->title('Health Check Completed')
                        ->body('Queue health monitoring completed. Check the widget for metrics.')
                        ->info()
                        ->send();
                }),

            Actions\Action::make('clear_failed')
                ->label('Clear All Failed Jobs')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear Failed Jobs')
                ->modalDescription('This will permanently delete all failed jobs. This action cannot be undone.')
                ->action(function () {
                    Artisan::call('queue:flush');

                    Notification::make()
                        ->title('Failed Jobs Cleared')
                        ->body('All failed jobs have been permanently removed from the database')
                        ->success()
                        ->send();
                }),
        ];
    }
}
