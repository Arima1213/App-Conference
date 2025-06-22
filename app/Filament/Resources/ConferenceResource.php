<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Models\Conference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                            Forms\Components\FileUpload::make('banner')
                                ->image()
                                ->directory('conference-banners')
                                ->disk('public')
                                ->maxSize(2048)
                                ->nullable()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('description')
                                ->rows(4)
                                ->maxLength(65535)
                                ->columnSpanFull(),
                            Forms\Components\Hidden::make('is_active')
                                ->default(true),
                        ]),
                    Forms\Components\Wizard\Step::make('Jadwal & Tempat')
                        ->schema([
                            Forms\Components\Repeater::make('schedules')
                                ->relationship('schedules')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->columnSpanFull()
                                        ->required(),
                                    Forms\Components\TextInput::make('subtitle')
                                        ->columnSpanFull()
                                        ->nullable(),
                                    Forms\Components\Textarea::make('description')
                                        ->columnSpanFull()
                                        ->nullable()->rows(2),
                                    Forms\Components\Select::make('speaker_id')
                                        ->relationship('speaker', 'name')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Speaker')
                                        ->getOptionLabelFromRecordUsing(function ($record) {
                                            // Tampilkan ikon bintang jika is_keynote = true
                                            return $record->name . ($record->is_keynote ? ' ⭐' : '');
                                        })
                                        ->extraAttributes([
                                            'x-data' => '{}',
                                        ])
                                        ->hintAction(
                                            fn() => Forms\Components\Actions\Action::make('addSpeaker')
                                                ->label('Tambah Speaker')
                                                ->url('/manage/speakers/create')
                                                ->openUrlInNewTab()
                                        ),
                                    Forms\Components\DateTimePicker::make('start_time')
                                        ->required()
                                        ->label('Start Date & Time')
                                        ->displayFormat('Y-m-d H:i')
                                        ->native(false),
                                    Forms\Components\DateTimePicker::make('end_time')
                                        ->required()
                                        ->label('End Date & Time')
                                        ->displayFormat('Y-m-d H:i')
                                        ->native(false),
                                ])
                                ->createItemButtonLabel('Add Schedule')
                                ->columns(2),

                            Forms\Components\Repeater::make('venues')
                                ->relationship('venues')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->columnSpanFull()
                                        ->required(),
                                    Forms\Components\TextInput::make('address')
                                        ->columnSpanFull()
                                        ->required(),
                                    Forms\Components\TextInput::make('map_url')
                                        ->columnSpanFull()
                                        ->url()->nullable(),
                                ])
                                ->createItemButtonLabel('Add Venue')
                                ->columns(2),
                        ]),
                    Forms\Components\Wizard\Step::make('Sponsor')
                        ->schema([
                            Forms\Components\Repeater::make('sponsors')
                                ->relationship('sponsors')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\FileUpload::make('logo')
                                        ->image()
                                        ->directory('sponsor-logos')
                                        ->disk('public')
                                        ->maxSize(1024)
                                        ->nullable()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('website')
                                        ->url()
                                        ->nullable()
                                        ->columnSpanFull(),
                                ])
                                ->createItemButtonLabel('Add Sponsor')
                                ->columns(2),
                        ]),
                    Forms\Components\Wizard\Step::make('Important Dates')
                        ->schema([
                            Forms\Components\Repeater::make('importantDates')
                                ->relationship('importantDates')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\DatePicker::make('date')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('description')
                                        ->nullable()
                                        ->columnSpanFull(),
                                ])
                                ->createItemButtonLabel('Add Important Date')
                                ->columns(2),
                        ]),
                ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->submitAction(
                        request()->routeIs('filament.admin.resources.conferences.view')
                            ? null
                            : new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(
                                <<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                    >
                        Submit
                    </x-filament::button>
            BLADE
                            ))
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('schedules_count')
                    ->counts('schedules')
                    ->label('Schedules')
                    ->sortable(),
                Tables\Columns\TextColumn::make('venues_count')
                    ->counts('venues')
                    ->label('Venues')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}