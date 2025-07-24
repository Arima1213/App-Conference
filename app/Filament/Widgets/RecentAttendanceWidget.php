<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\AttendanceLog;

class RecentAttendanceWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Check-ins';
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AttendanceLog::query()
                    ->with(['participant.user', 'scannedBy'])
                    ->latest('scanned_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('participant.user.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('participant.participant_code')
                    ->label('Code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'present' => 'success',
                        'not_present' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'present' => 'heroicon-o-check-circle',
                        'not_present' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('scannedBy.name')
                    ->label('Scanned By')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scanned_at')
                    ->label('Time')
                    ->dateTime('H:i, d M Y')
                    ->sortable(),
            ])
            ->defaultSort('scanned_at', 'desc')
            ->striped()
            ->paginated(false);
    }
}
