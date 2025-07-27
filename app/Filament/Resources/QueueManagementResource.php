<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Query\Builder;

class QueueManagementResource extends Resource
{
    protected static ?string $model = null; // We'll use custom queries

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Queue Management';

    protected static ?string $modelLabel = 'Queue Job';

    protected static ?string $pluralModelLabel = 'Queue Jobs';

    protected static ?string $navigationGroup = 'System';

    public static function table(Table $table): Table
    {
        return $table
            ->query(self::getJobsQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Job ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('queue')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'high' => 'danger',
                        'default' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('job_type')
                    ->label('Type')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('attempts')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => $state > 2 ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('available_at')
                    ->label('Available At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'processing' => 'heroicon-o-arrow-path',
                        'completed' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('queue')
                    ->options([
                        'default' => 'Default',
                        'high' => 'High Priority',
                        'low' => 'Low Priority',
                        'emails' => 'Email Queue',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Action::make('retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn($record) => $record->status === 'failed')
                    ->action(function ($record) {
                        try {
                            Artisan::call('queue:retry', ['id' => $record->uuid ?? $record->id]);

                            Notification::make()
                                ->title('Job Restarted')
                                ->body("Job #{$record->id} has been queued for retry")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Restart Failed')
                                ->body('Could not restart the job: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->status === 'failed') {
                            DB::table('failed_jobs')->where('id', $record->id)->delete();
                        } else {
                            DB::table('jobs')->where('id', $record->id)->delete();
                        }

                        Notification::make()
                            ->title('Job Deleted')
                            ->body("Job #{$record->id} has been deleted")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('retry_selected')
                    ->label('Retry Selected')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function ($records) {
                        $retried = 0;
                        foreach ($records as $record) {
                            if ($record->status === 'failed') {
                                try {
                                    Artisan::call('queue:retry', ['id' => $record->uuid ?? $record->id]);
                                    $retried++;
                                } catch (\Exception $e) {
                                    // Log error but continue
                                }
                            }
                        }

                        Notification::make()
                            ->title('Bulk Retry Completed')
                            ->body("Successfully retried {$retried} jobs")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteBulkAction::make()
                    ->action(function ($records) {
                        $deleted = 0;
                        foreach ($records as $record) {
                            if ($record->status === 'failed') {
                                DB::table('failed_jobs')->where('id', $record->id)->delete();
                            } else {
                                DB::table('jobs')->where('id', $record->id)->delete();
                            }
                            $deleted++;
                        }

                        Notification::make()
                            ->title('Bulk Delete Completed')
                            ->body("Successfully deleted {$deleted} jobs")
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('restart_queue')
                    ->label('Restart Queue')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function () {
                        Artisan::call('queue:restart');

                        Notification::make()
                            ->title('Queue Restarted')
                            ->body('All queue workers have been signaled to restart')
                            ->success()
                            ->send();
                    }),

                Action::make('clear_failed')
                    ->label('Clear Failed Jobs')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        Artisan::call('queue:flush');

                        Notification::make()
                            ->title('Failed Jobs Cleared')
                            ->body('All failed jobs have been removed')
                            ->success()
                            ->send();
                    }),

                Action::make('monitor_health')
                    ->label('Check Health')
                    ->icon('heroicon-o-heart')
                    ->color('info')
                    ->action(function () {
                        Artisan::call('queue:monitor', ['--check' => true]);

                        Notification::make()
                            ->title('Health Check Completed')
                            ->body('Queue health has been analyzed. Check logs for details.')
                            ->info()
                            ->send();
                    }),
            ])
            ->poll('30s'); // Auto-refresh every 30 seconds
    }

    protected static function getJobsQuery(): \Illuminate\Database\Query\Builder
    {
        // Union query to get both pending and failed jobs
        return DB::query()
            ->select([
                'id',
                'queue',
                'payload',
                'attempts',
                'created_at',
                'available_at',
                'reserved_at',
                DB::raw("'pending' as status"),
                DB::raw("NULL as uuid"),
                DB::raw("NULL as failed_at")
            ])
            ->from('jobs')
            ->unionAll(
                DB::query()
                    ->select([
                        'id',
                        DB::raw("'failed' as queue"),
                        'payload',
                        DB::raw("'0' as attempts"),
                        DB::raw("UNIX_TIMESTAMP(failed_at) as created_at"),
                        DB::raw("UNIX_TIMESTAMP(failed_at) as available_at"),
                        DB::raw("NULL as reserved_at"),
                        DB::raw("'failed' as status"),
                        'uuid',
                        'failed_at'
                    ])
                    ->from('failed_jobs')
            )
            ->orderBy('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false; // Prevent manual job creation
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\QueueManagementResource\Pages\ListQueueManagement::route('/'),
        ];
    }
}

// Remove the embedded class since we created it separately
