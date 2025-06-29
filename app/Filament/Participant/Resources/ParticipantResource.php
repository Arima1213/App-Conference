<?php

namespace App\Filament\Participant\Resources;

use App\Filament\Participant\Resources\ParticipantResource\Pages;
use App\Models\Conference;
use App\Models\Membership;
use App\Models\Participant;
use App\Models\SeminarFee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Filament\Notifications\Notification;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $request = request();
        $conferenceId = null;

        if ($request->query->has('conference')) {
            try {
                $conferenceId = Crypt::decryptString($request->query('conference'));
            } catch (\Exception) {
                $conferenceId = null;
            }
        }

        $userId = Auth::user()->id;

        $hasRegistered = Participant::where('user_id', $userId)
            ->exists();

        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Personal Information')
                        ->schema([
                            Forms\Components\Hidden::make('user_id')
                                ->default($userId),
                            Forms\Components\Hidden::make('conference_id')
                                ->default($conferenceId),
                            Forms\Components\TextInput::make('nik')
                                ->label('National Identification Number')
                                ->numeric()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('educational_institution_id')
                                ->label('University')
                                ->required()
                                ->relationship('educationalInstitution', 'nama_pt')
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->required()
                                ->maxLength(20),
                            Forms\Components\TextInput::make('paper_title')
                                ->label('Paper Title (optional)')
                                ->maxLength(255),
                        ])
                        ->columns(2),

                    Forms\Components\Wizard\Step::make('Select Seminar Fee')
                        ->schema([
                            Forms\Components\Select::make('seminar_fee_id')
                                ->label('Seminar Fee')
                                ->required()
                                ->options(function () use ($conferenceId, $hasRegistered) {
                                    return SeminarFee::where('conference_id', $conferenceId)
                                        ->get()
                                        ->mapWithKeys(function ($fee) use ($hasRegistered) {
                                            $price = $hasRegistered ? $fee->regular_price : $fee->early_bird_price;
                                            $label = "{$fee->type} - {$fee->category} (Rp " . number_format($price, 0, ',', '.') . ")";
                                            return [$fee->id => $label];
                                        })
                                        ->toArray();
                                })
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $fee = SeminarFee::find($state);
                                    $set('is_member_fee', $fee?->is_member);
                                })
                                ->searchable(),

                            Forms\Components\Placeholder::make('price_info')
                                ->label('Price Type')
                                ->content(function () use ($hasRegistered) {
                                    return $hasRegistered
                                        ? 'You are eligible for the Regular price.'
                                        : 'You are eligible for the Early Bird price.';
                                }),

                            Forms\Components\Hidden::make('is_member_fee')
                                ->default(false),

                            Forms\Components\Fieldset::make('Membership Verification')
                                ->visible(fn(callable $get) => $get('is_member_fee'))
                                ->schema([
                                    Forms\Components\TextInput::make('membership_number')
                                        ->label('Membership Number')
                                        ->maxLength(50)
                                        ->required(),

                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('verifyMembership')
                                            ->label('Verify Membership')
                                            ->action(function (array $data, callable $set, callable $get) {
                                                $membership = Membership::where('no_anggota', $data['membership_number'])->first();

                                                if (!$membership) {
                                                    Notification::make()
                                                        ->danger()
                                                        ->title('Membership Not Found')
                                                        ->body('The provided membership number is not registered.')
                                                        ->send();
                                                    $set('membership_id', null);
                                                    return;
                                                }

                                                Notification::make()
                                                    ->success()
                                                    ->title('Membership Verified')
                                                    ->body('Membership number is valid.')
                                                    ->send();

                                                $set('membership_id', $membership->id);
                                            }),
                                    ])
                                ]),

                            Forms\Components\Hidden::make('membership_id'),
                        ])
                ])->columnSpanFull()
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
            ->columns([]);
    }

    public static function getRelations(): array
    {
        return [];
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