<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Models\Conference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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
                    ->label('Conference Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn($record) => $record->description ? Str::limit($record->description, 60) : null),
                Tables\Columns\TextColumn::make('schedules')
                    ->label('Schedules')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->schedules->map(function ($schedule) {
                            $start = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d H:i') : '-';
                            $end = $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('Y-m-d H:i') : '-';
                            return "{$schedule->title}<br><small>{$start} - {$end}</small>";
                        })->implode('<br><br>');
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('venues')
                    ->label('Venues')
                    ->formatStateUsing(function ($record) {
                        return $record->venues->map(function ($venue) {
                            $name = \Illuminate\Support\Str::limit($venue->name, 25);
                            $address = \Illuminate\Support\Str::limit($venue->address, 35);
                            // Alamat di bawah nama, tanpa ikon
                            return $name . '<br><small>' . $address . '</small>';
                        })->implode('<br>');
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('sponsors')
                    ->label('Sponsors')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->sponsors->map(function ($sponsor) {
                            $name = e($sponsor->name);
                            if ($sponsor->website) {
                                $name =  $name;
                            }
                            return "{$name}";
                        })->implode('<br>');
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('importantDates_count')
                    ->counts('importantDates')
                    ->label('Important Dates')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Details'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected'),
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
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}