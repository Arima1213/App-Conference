<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers;
use App\Models\Speaker;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpeakerResource extends Resource
{
    use HasPageShield;

    protected function getShieldRedirectPath(): string
    {
        return url('/manage');
    }
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                    ->size(40)
                    ->tooltip(fn($record) => $record->name),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->description(fn($record) => $record->position),
                Tables\Columns\TextColumn::make('position')
                    ->label('Position / Title')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_keynote')
                    ->label('Keynote Speaker')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus')
                    ->color(fn($state) => $state ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_keynote')
                    ->label('Keynote Speaker')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->placeholder('All'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-o-trash'),
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
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit' => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
