<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeynoteSpeakerResource\Pages;
use App\Filament\Resources\KeynoteSpeakerResource\RelationManagers;
use App\Models\KeynoteSpeaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeynoteSpeakerResource extends Resource
{
    protected static ?string $model = KeynoteSpeaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('institution')
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->maxLength(65535),
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->directory('keynote-speakers/photos')
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('institution')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bio')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
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
            'index' => Pages\ListKeynoteSpeakers::route('/'),
            'create' => Pages\CreateKeynoteSpeaker::route('/create'),
            'edit' => Pages\EditKeynoteSpeaker::route('/{record}/edit'),
        ];
    }
}