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
use Illuminate\Support\Facades\Auth;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $request = request();
        $conferenceId = null;

        // Ambil parameter dari query string, bukan dari request body
        if ($request->query->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->query('conference'));
            } catch (\Exception) {
                $conferenceId = null;
            }
        }

        $userId = Auth::user()->id;

        // Cek apakah user sudah pernah mendaftar di conference ini
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
                                ->maxLength(255)
                                ->placeholder('Enter your NIK')
                                ->helperText('Please enter your valid national identification number.'),
                            Forms\Components\Select::make('educational_institution_id')
                                ->label('University')
                                ->required()
                                ->relationship('educationalInstitution', 'nama_pt')
                                ->searchable()
                                ->placeholder('Select your university')
                                ->preload()
                                ->helperText('Choose the university you are currently enrolled in or graduated from.'),
                            Forms\Components\TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->placeholder('Enter your phone number')
                                ->helperText('Enter an active phone number for contact purposes.'),
                            Forms\Components\TextInput::make('paper_title')
                                ->label('Paper Title (optional)')
                                ->maxLength(255)
                                ->placeholder('Enter your paper title (if applicable)')
                                ->helperText('If you are submitting a paper, please provide the title. Otherwise, leave this blank.'),
                        ])
                        ->columns(2),
                    Forms\Components\Wizard\Step::make('Select Seminar Fee')
                        ->schema([
                            Forms\Components\Select::make('seminar_fee_id')
                                ->relationship('seminarFee')
                                ->label('Seminar Fee')
                                ->required()
                                ->options(function () use ($conferenceId, $hasRegistered) {
                                    return \App\Models\SeminarFee::query()
                                        ->where('conference_id', $conferenceId)
                                        ->get()
                                        ->mapWithKeys(function ($fee) use ($hasRegistered) {
                                            $price = $hasRegistered ? $fee->regular_price : $fee->early_bird_price;
                                            $label = "{$fee->type} - {$fee->category} (Rp " . number_format($price, 0, ',', '.') . ")";
                                            return [$fee->id => $label];
                                        })
                                        ->toArray();
                                })
                                ->searchable()
                                ->placeholder('Select seminar fee')
                                ->helperText('Choose the seminar fee according to your category and registration status.'),
                            Forms\Components\Placeholder::make('price_info')
                                ->label('Price Type')
                                ->content(function () use ($hasRegistered) {
                                    return $hasRegistered
                                        ? 'You are eligible for the Regular price because you have previously registered for this conference.'
                                        : 'You are eligible for the Early Bird price because you have not registered for this conference before.';
                                }),
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