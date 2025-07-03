<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceLogResource\Pages;
use App\Filament\Resources\AttendanceLogResource\RelationManagers;
use App\Models\AttendanceLog;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceLogResource extends Resource
{
    use HasPageShield;

    protected function getShieldRedirectPath(): string
    {
        return url('/manage');
    }
    protected static ?string $model = AttendanceLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    /**
     * Define the form schema for creating and editing AttendanceLog records.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('participant_id')
                ->label('Participant')
                ->relationship('participant', 'name')
                ->searchable()
                ->required()
                ->preload(),

            Forms\Components\Select::make('conference_id')
                ->label('Conference')
                ->relationship('conference', 'name')
                ->searchable()
                ->required()
                ->preload(),

            Forms\Components\Select::make('scanned_by')
                ->label('Scanned By')
                ->relationship('scannedBy', 'name')
                ->searchable()
                ->required()
                ->preload(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'present' => 'Present',
                    'not_present' => 'Not Present',
                ])
                ->required()
                ->default('present'),

            Forms\Components\DateTimePicker::make('scanned_at')
                ->label('Scanned At')
                ->required()
                ->displayFormat('Y-m-d H:i:s')
                ->seconds(false),
        ]);
    }

    /**
     * Define the table schema for displaying AttendanceLog records.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('conference.name')
                    ->label('Conference')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scannedBy.name')
                    ->label('Scanned By')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'present',
                        'danger' => 'not_present',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('scanned_at')
                    ->label('Scanned At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'present' => 'Present',
                        'not_present' => 'Not Present',
                    ]),

                Tables\Filters\SelectFilter::make('conference_id')
                    ->label('Conference')
                    ->relationship('conference', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View Details'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected'),
                ]),
            ])
            ->defaultSort('scanned_at', 'desc');
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
