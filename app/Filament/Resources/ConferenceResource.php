<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Filament\Resources\ConferenceResource\RelationManagers;
use App\Models\Conference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConferenceResource extends Resource
{
    protected static ?string $model = Conference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Informasi')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2),
                            Forms\Components\Textarea::make('description')
                                ->rows(4)
                                ->maxLength(65535)
                                ->columnSpan(2),
                        ]),
                    Forms\Components\Wizard\Step::make('Jadwal & Tempat')
                        ->schema([
                            Forms\Components\Repeater::make('schedules')
                                ->relationship('schedules') // penting agar otomatis tersimpan
                                ->schema([
                                    Forms\Components\Select::make('speaker_id')
                                        ->relationship('speaker', 'name')
                                        ->required()
                                        ->label('Speaker'),
                                    Forms\Components\TimePicker::make('start_time')->required(),
                                    Forms\Components\TimePicker::make('end_time')->required(),
                                    Forms\Components\TextInput::make('title')->required(),
                                    Forms\Components\TextInput::make('subtitle')->nullable(),
                                    Forms\Components\Textarea::make('description')->nullable()->rows(2),
                                ])
                                ->createItemButtonLabel('Tambah Jadwal')
                                ->columns(2),

                            Forms\Components\Repeater::make('venues')
                                ->relationship('venues') // relasi otomatis
                                ->schema([
                                    Forms\Components\TextInput::make('name')->required(),
                                    Forms\Components\TextInput::make('address')->required(),
                                    Forms\Components\TextInput::make('map_url')->url()->nullable(),
                                ])
                                ->createItemButtonLabel('Tambah Lokasi')
                                ->columns(2),
                        ]),
                    Forms\Components\Wizard\Step::make('Banner & Status')
                        ->schema([
                            Forms\Components\FileUpload::make('banner')
                                ->image()
                                ->directory('conference-banners')
                                ->maxSize(2048)
                                ->nullable()
                                ->columnSpan(2),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->columnSpan(2),
                        ]),
                ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->submitAction(new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                    >
                        Submit
                    </x-filament::button>
            BLADE
                    )))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.name')
                    ->label('Schedule')
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Venue')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('banner')
                    ->label('Banner')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}