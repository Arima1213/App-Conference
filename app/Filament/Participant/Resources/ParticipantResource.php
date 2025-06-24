<?php

namespace App\Filament\Participant\Resources;

use App\Filament\Participant\Resources\ParticipantResource\Pages;
use App\Filament\Participant\Resources\ParticipantResource\RelationManagers;
use App\Models\Conference;
use App\Models\Participant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $request = request();
        $conferenceId = null;

        if ($request->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->get('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        $userId = Auth::user()->id;

        return $form
            ->schema([
                Forms\Components\Section::make('Data Peserta')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default($userId),
                        Forms\Components\Hidden::make('conference_id')
                            ->default($conferenceId),
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('university')
                            ->label('Universitas')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('paper_title')
                            ->label('Judul Paper')
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('university')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('participant_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paper_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qrcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\IconColumn::make('seminar_kit_status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
