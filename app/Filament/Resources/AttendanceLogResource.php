<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceLogResource\Pages;
use App\Filament\Resources\AttendanceLogResource\RelationManagers;
use App\Models\AttendanceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceLogResource extends Resource
{
    protected static ?string $model = AttendanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('participant_id')
                    ->label('Participant')
                    ->relationship('participant', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('scanned_by')
                    ->label('Scanned By')
                    ->relationship('scannedBy', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'not_present' => 'Not Present',
                    ])
                    ->required()
                    ->default('present'),

                Forms\Components\DateTimePicker::make('scanned_at')
                    ->label('Scanned At')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scannedBy.name')
                    ->label('Scanned By')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        'present' => 'Present',
                        'not_present' => 'Not Present',
                    ])
                    ->colors([
                        'success' => 'present',
                        'danger' => 'not_present',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('scanned_at')
                    ->label('Scanned At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'not_present' => 'Not Present',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceLogs::route('/'),
            'create' => Pages\CreateAttendanceLog::route('/create'),
            'edit' => Pages\EditAttendanceLog::route('/{record}/edit'),
        ];
    }
}