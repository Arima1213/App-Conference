<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Models\Conference;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * Helper function to generate a fee input field for the conference form.
 */
function feeInput(string $label, string $type, string $currency)
{
    return \Filament\Forms\Components\TextInput::make("fees.{$type}.{$label}")
        ->label("{$label} ({$currency})")
        ->numeric()
        ->minValue(0)
        ->nullable()
        ->columnSpanFull();
}

class ConferenceResource extends Resource
{
    use HasPageShield;

    protected function getShieldRedirectPath(): string
    {
        return url('/manage');
    }
    protected static ?string $model = Conference::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Information')
                        ->schema([
                            Forms\Components\FileUpload::make('banner')
                                ->image()
                                ->directory('conference-banners')
                                ->disk('public')
                                ->maxSize(5120)
                                ->nullable()
                                ->label('Banner Image')
                                ->helperText('Upload a banner image for the conference (max 5MB).')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->label('Conference Title')
                                ->placeholder('Enter the conference title')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('description')
                                ->rows(4)
                                ->maxLength(65535)
                                ->label('Description')
                                ->placeholder('Provide a brief description of the conference')
                                ->columnSpanFull(),
                            // Forms\Components\Toggle::make('is_active')
                            //     ->label('Active')
                            //     ->default(true)
                            //     ->helperText('Set whether this conference is currently active.'),
                        ]),
                    Forms\Components\Wizard\Step::make('Schedules')
                        ->schema([
                            Forms\Components\Repeater::make('schedules')
                                ->reorderableWithButtons()
                                ->minItems(1)
                                ->itemLabel(fn(array $state): ?string => $state['title'] ?? null)
                                ->collapsible()
                                ->relationship('schedules')
                                ->label('Schedules')
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\Hidden::make('id'),
                                    Forms\Components\TextInput::make('title')
                                        ->required()
                                        ->label('Schedule Title')
                                        ->placeholder('Enter schedule title')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('subtitle')
                                        ->nullable()
                                        ->label('Subtitle')
                                        ->placeholder('Enter subtitle (optional)')
                                        ->columnSpanFull(),
                                    Forms\Components\Textarea::make('description')
                                        ->nullable()
                                        ->rows(2)
                                        ->label('Description')
                                        ->placeholder('Describe this schedule (optional)')
                                        ->columnSpanFull(),
                                    Forms\Components\Select::make('speaker_id')
                                        ->relationship('speaker', 'name')
                                        ->required()
                                        ->label('Speaker')
                                        ->getOptionLabelFromRecordUsing(function ($record) {
                                            return $record->name . ($record->is_keynote ? ' ⭐' : '');
                                        })
                                        ->hint('Select the speaker for this schedule')
                                        ->hintAction(
                                            fn() => Forms\Components\Actions\Action::make('addSpeaker')
                                                ->label('Add New Speaker')
                                                ->url('/manage/speakers/create')
                                                ->openUrlInNewTab()
                                        )
                                        ->columnSpanFull(),
                                    Forms\Components\DateTimePicker::make('start_time')
                                        ->required()
                                        ->label('Start Date & Time')
                                        ->displayFormat('Y-m-d H:i')
                                        ->withoutSeconds()
                                        ->default(null),
                                    Forms\Components\DateTimePicker::make('end_time')
                                        ->required()
                                        ->label('End Date & Time')
                                        ->displayFormat('Y-m-d H:i')
                                        ->withoutSeconds()
                                        ->default(null),
                                ])
                                ->createItemButtonLabel('Add Schedule')
                                ->columns(2)
                                ->helperText('Add one or more schedules for this conference.'),
                        ]),
                    Forms\Components\Wizard\Step::make('Venues')
                        ->schema([
                            Forms\Components\Repeater::make('venues')
                                ->relationship('venues')
                                ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                ->label('Venues')
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Venue Name')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('address')
                                        ->required()
                                        ->label('Address')
                                        ->reactive()
                                        ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                            $encodedAddress = urlencode($state);
                                            $embedUrl = "https://maps.google.com/maps?q={$encodedAddress}&output=embed";
                                            $set('map_url', $embedUrl);
                                        })
                                        ->placeholder('Enter venue address')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('map_url')
                                        ->label('Google Maps Embed URL')
                                        ->readOnly()
                                        ->helperText('Auto-filled from address')
                                        ->columnSpanFull(),
                                ])
                                // ->addActionLabel('Add Venue') // Remove or comment out this line to hide the button
                                ->columns(2)
                                ->helperText('List all venues for this conference.')
                                ->addable(false)
                        ]),
                    Forms\Components\Wizard\Step::make('Sponsors')
                        ->schema([
                            Forms\Components\Repeater::make('sponsors')
                                ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                ->relationship('sponsors')
                                ->label('Sponsors')
                                ->columnSpanFull()
                                ->reorderableWithButtons()
                                ->minItems(1)
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Hidden::make('id'),
                                    Forms\Components\Select::make('level')
                                        ->label('Sponsor Level')
                                        ->options([
                                            'gold' => 'Gold',
                                            'silver' => 'Silver',
                                            'bronze' => 'Bronze',
                                        ])
                                        ->columnSpanFull()
                                        ->required()
                                        ->helperText('Select the sponsorship level.'),
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Sponsor Name')
                                        ->placeholder('Enter sponsor name')
                                        ->columnSpanFull(),
                                    Forms\Components\FileUpload::make('logo')
                                        ->image()
                                        ->directory('sponsor-logos')
                                        ->disk('public')
                                        ->maxSize(1024)
                                        ->nullable()
                                        ->label('Logo')
                                        ->helperText('Upload sponsor logo (max 1MB).')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('website')
                                        ->nullable()
                                        ->url()
                                        ->label('Website')
                                        ->placeholder('Enter sponsor website (optional)')
                                        ->columnSpanFull(),
                                ])
                                ->createItemButtonLabel('Add Sponsor')
                                ->columns(2)
                                ->helperText('Add sponsors supporting this conference.'),
                        ]),
                    Forms\Components\Wizard\Step::make('Seminar Fees')
                        ->schema([
                            Forms\Components\Repeater::make('seminarFees')
                                ->itemLabel(fn(array $state): ?string => isset($state['type'], $state['category']) ? "{$state['type']} | {$state['category']}" : ($state['category'] ?? null))
                                ->relationship('seminarFees')
                                ->reorderableWithButtons()
                                ->minItems(1)
                                ->collapsible()
                                ->label('Seminar Fees')
                                ->schema([
                                    Forms\Components\Select::make('type')
                                        ->label('Type')
                                        ->options([
                                            'online' => 'Online',
                                            'offline' => 'Offline',
                                        ])
                                        ->required()
                                        ->helperText('Select whether this fee applies to online or offline participation.'),
                                    Forms\Components\Select::make('category')
                                        ->label('Option')
                                        ->options([
                                            'Conference | Member' => 'Conference | Member',
                                            'Conference | Non-Member' => 'Conference | Non-Member',
                                            'Deliberation | Member' => 'Deliberation | Member',
                                            'Deliberation | Non-Member' => 'Deliberation | Non-Member',
                                            'Deliberation & Conference | Member' => 'Deliberation & Conference | Member',
                                            'Deliberation & Conference | Non-Member' => 'Deliberation & Conference | Non-Member',
                                        ])
                                        ->required()
                                        ->helperText('Choose the participant category for this fee option.')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // Aktifkan is_member jika kategori mengandung "Member" (bukan "Non-Member" atau "Non IEEE Member")
                                            $isMember = false;
                                            if (
                                                (str_contains($state, 'Member') && !str_contains($state, 'Non-Member'))
                                            ) {
                                                $isMember = true;
                                            }
                                            $set('is_member', $isMember);
                                        }),
                                    Forms\Components\Hidden::make('is_member'),
                                    Forms\Components\TextInput::make('early_bird_price')
                                        ->label('Early Bird Price')
                                        ->numeric()
                                        ->minValue(0)
                                        ->required()
                                        ->helperText('Enter the discounted price for early bird registration.'),
                                    Forms\Components\TextInput::make('regular_price')
                                        ->label('Regular Price')
                                        ->numeric()
                                        ->minValue(0)
                                        ->required()
                                        ->helperText('Enter the standard price for regular registration.'),
                                ])
                                ->addActionLabel('Add Seminar Fee')
                                ->columns(2)
                                ->helperText('Add seminar fees for each participant type and category.')
                                ->defaultItems(1),
                        ])
                        ->columns(1),
                    Forms\Components\Wizard\Step::make('Important Dates')
                        ->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\Repeater::make('importantDates')
                                ->reorderableWithButtons()
                                ->itemLabel(fn(array $state): ?string => $state['title'] ?? null)
                                ->minItems(1)
                                ->collapsible()
                                ->relationship('importantDates')
                                ->label('Important Dates')
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->required()
                                        ->label('Date Title')
                                        ->placeholder('Enter important date title')
                                        ->columnSpanFull(),
                                    Forms\Components\DatePicker::make('date')
                                        ->required()
                                        ->label('Date')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('description')
                                        ->nullable()
                                        ->label('Description')
                                        ->placeholder('Describe this date (optional)')
                                        ->columnSpanFull(),
                                ])
                                ->createItemButtonLabel('Add Important Date')
                                ->columns(2)
                                ->helperText('Specify important dates related to this conference.'),
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
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => !$record->is_active)
                    ->action(function ($record) {
                        $record->is_active = true;
                        $record->save();
                    }),
                Tables\Actions\Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->is_active)
                    ->action(function ($record) {
                        $record->is_active = false;
                        $record->save();
                    }),
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
