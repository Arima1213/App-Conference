<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\EducationalInstitution;
use Illuminate\Database\Eloquent\Builder;

class TopInstitutionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Educational Institutions';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                EducationalInstitution::query()
                    ->withCount('participants')
                    ->orderBy('participants_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Institution Name')
                    ->searchable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    }),

                Tables\Columns\TextColumn::make('participants_count')
                    ->label('Participants')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= 10) return 'success';
                        if ($state >= 5) return 'warning';
                        return 'gray';
                    })
                    ->icon(function ($state) {
                        if ($state >= 10) return 'heroicon-o-trophy';
                        if ($state >= 5) return 'heroicon-o-star';
                        return 'heroicon-o-users';
                    }),
            ])
            ->defaultSort('participants_count', 'desc')
            ->striped()
            ->paginated(false);
    }
}
