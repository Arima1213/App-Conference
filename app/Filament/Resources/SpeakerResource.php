<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers;
use App\Models\Speaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Speaker Information')
                    ->description('Please complete the speaker data accurately.')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('Speaker Photo')
                            ->image()
                            ->directory('speakers/photos')
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->maxSize(2048)
                            ->helperText('Format: JPG/PNG, Max 2MB, Ratio 1:1')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder('Enter speaker name'),
                        Forms\Components\TextInput::make('position')
                            ->label('Position/Title')
                            ->maxLength(255)
                            ->placeholder('Example: Business Manager'),
                        Forms\Components\Textarea::make('bio')
                            ->label('Biography')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Write a brief biography of the speaker'),
                        Forms\Components\Toggle::make('is_keynote')
                            ->label('Keynote Speaker')
                            ->helperText('Check if the speaker is a keynote speaker'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->size(36),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_keynote')
                    ->label('Keynote')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_keynote')
                    ->label('Keynote Speaker')
                    ->trueLabel('Yes')
                    ->falseLabel('No'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit' => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
