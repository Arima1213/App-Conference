<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantResource\Pages;
use App\Filament\Resources\ParticipantResource\RelationManagers;
use App\Models\Participant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;
    // logo icon participant
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->placeholder('Select a user'),
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->maxLength(255)
                    ->placeholder('Enter National Identification Number'),
                Forms\Components\Select::make('educational_institution_id')
                    ->label('University')
                    ->relationship('educationalInstitution', 'nama_pt')
                    ->searchable()
                    ->required()
                    ->placeholder('Select university'),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter phone number'),
                Forms\Components\TextInput::make('participant_code')
                    ->label('Participant Code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Enter unique participant code'),
                Forms\Components\TextInput::make('paper_title')
                    ->label('Paper Title')
                    ->maxLength(255)
                    ->placeholder('Enter paper title (if any)'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('educationalInstitution.nama_pt')
                    ->label('University')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone Number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('participant_code')
                    ->label('Participant Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paper_title')
                    ->label('Paper Title')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'unverified',
                        'success' => 'verified',
                        'primary' => 'arrived',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('seminar_kit_status')
                    ->label('Seminar Kit')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Received' : 'Unreceived')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unverified' => 'Unverified',
                        'verified' => 'Verified',
                        'arrived' => 'Arrived',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('arrived')
                        ->label('Arrived')
                        ->icon('heroicon-o-user-group')
                        ->color('primary')
                        ->visible(fn($record) => $record->status === 'verified')
                        ->action(function ($record) {
                            $record->status = 'arrived';
                            $record->save();
                        }),
                    Tables\Actions\Action::make('toggle_seminar_kit')
                        ->label(fn($record) => $record->seminar_kit_status ? 'Mark as Not Received' : 'Mark as Received')
                        ->icon(fn($record) => $record->seminar_kit_status ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn($record) => $record->seminar_kit_status ? 'danger' : 'success')
                        ->action(function ($record) {
                            $record->seminar_kit_status = !$record->seminar_kit_status;
                            $record->save();
                        }),
                ])->label('More Actions')->icon('heroicon-o-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListParticipants::route('/'),
        ];
    }
}