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
                Forms\Components\TextInput::make('university')
                    ->label('University')
                    ->maxLength(255)
                    ->placeholder('Enter university name'),
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
                Forms\Components\TextInput::make('qrcode')
                    ->label('QR Code')
                    ->maxLength(255)
                    ->placeholder('Enter QR code'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'unverified' => 'Unverified',
                        'verified' => 'Verified',
                        'arrived' => 'Arrived',
                    ])
                    ->default('unverified'),
                Forms\Components\Toggle::make('seminar_kit_status')
                    ->label('Seminar Kit Received')
                    ->required(),
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
                Tables\Columns\TextColumn::make('university')
                    ->label('University')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('participant_code')
                    ->label('Participant Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paper_title')
                    ->label('Paper Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qrcode')
                    ->label('QR Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'secondary' => 'unverified',
                        'success' => 'verified',
                        'primary' => 'arrived',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('seminar_kit_status')
                    ->label('Seminar Kit Received')
                    ->boolean()
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'create' => Pages\CreateParticipant::route('/create'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
