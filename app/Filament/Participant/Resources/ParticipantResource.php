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
                    Forms\Components\Wizard\Step::make('Informasi Pribadi')
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
                                ->placeholder('Enter your NIK'),
                            Forms\Components\Select::make('educational_institution_id')
                                ->label('University')
                                ->required()
                                ->relationship('educationalInstitution', 'nama_pt')
                                ->searchable()
                                ->placeholder('Select your university')
                                ->preload(),
                            Forms\Components\TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->placeholder('Enter your phone number'),
                            Forms\Components\TextInput::make('paper_title')
                                ->label('Paper Title(optional)')
                                ->maxLength(255)
                                ->placeholder('Enter your paper title (if applicable)'),
                        ])
                        ->columns(2),
                    Forms\Components\Wizard\Step::make('Pilih Seminar Fee')
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
                                ->placeholder('Pilih seminar fee'),
                            Forms\Components\Placeholder::make('price_info')
                                ->label('Tipe Harga')
                                ->content(function () use ($hasRegistered) {
                                    return $hasRegistered
                                        ? 'Anda mendapatkan harga Reguler karena sudah pernah mendaftar pada conference.'
                                        : 'Anda mendapatkan harga Early Bird karena belum pernah mendaftar pada conference.';
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