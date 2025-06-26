<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationalInstitutionResource\Pages;
use App\Filament\Resources\EducationalInstitutionResource\RelationManagers;
use App\Models\EducationalInstitution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EducationalInstitutionResource extends Resource
{
    protected static ?string $model = EducationalInstitution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Institution Info')
                        ->description('Fill in the main institution details.')
                        ->schema([
                            Forms\Components\TextInput::make('lembaga')
                                ->label('Institution')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter institution name')
                                ->hint('Enter the official name of the institution.'),
                            Forms\Components\TextInput::make('kelompok_koordinator')
                                ->label('Coordinator Group')
                                ->maxLength(255)
                                ->placeholder('Enter coordinator group')
                                ->hint('Specify the coordinator group if applicable.'),
                            Forms\Components\TextInput::make('npsn')
                                ->label('NPSN')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter NPSN')
                                ->hint('Enter the National School Principal Number (NPSN).'),
                            Forms\Components\TextInput::make('nama_pt')
                                ->label('University Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter university name')
                                ->hint('Enter the full name of the university.'),
                            Forms\Components\TextInput::make('nm_bp')
                                ->label('BP Name')
                                ->maxLength(255)
                                ->placeholder('Enter BP name')
                                ->hint('Enter the name of the BP (if any).'),
                        ]),
                    Forms\Components\Wizard\Step::make('Location')
                        ->description('Provide the institution\'s location details.')
                        ->schema([
                            Forms\Components\TextInput::make('provinsi_pt')
                                ->label('Province')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter province')
                                ->hint('Select or enter the province where the institution is located.'),
                            Forms\Components\Textarea::make('jln')
                                ->label('Address')
                                ->rows(2)
                                ->maxLength(500)
                                ->placeholder('Enter address')
                                ->columnSpanFull()
                                ->hint('Enter the complete street address.'),
                            Forms\Components\TextInput::make('kec_pt')
                                ->label('District')
                                ->maxLength(255)
                                ->placeholder('Enter district')
                                ->hint('Enter the district (kecamatan) name.'),
                            Forms\Components\TextInput::make('kabupaten_kota')
                                ->label('City/Regency')
                                ->maxLength(255)
                                ->placeholder('Enter city or regency')
                                ->hint('Enter the city or regency (kabupaten/kota) name.'),
                        ]),
                    Forms\Components\Wizard\Step::make('Contact')
                        ->description('Enter contact and website information.')
                        ->schema([
                            Forms\Components\TextInput::make('website')
                                ->label('Website')
                                ->url()
                                ->maxLength(255)
                                ->placeholder('https://example.com')
                                ->hint('Enter the official website URL, starting with https://'),
                            Forms\Components\TextInput::make('no_tel')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20)
                                ->placeholder('Enter phone number')
                                ->hint('Enter a valid phone number for the institution.'),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Enter email address')
                                ->hint('Enter the official email address for correspondence.'),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pt')
                    ->label('University Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provinsi_pt')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provinsi_pt')
                    ->label('Province')
                    ->options(
                        EducationalInstitution::query()
                            ->distinct()
                            ->pluck('provinsi_pt', 'provinsi_pt')
                            ->filter()
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('nama_pt')
                    ->label('University Name')
                    ->options(
                        EducationalInstitution::query()
                            ->distinct()
                            ->pluck('nama_pt', 'nama_pt')
                            ->filter()
                            ->toArray()
                    ),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalCancelAction(false) // menghilangkan tombol Close, hanya menyisakan icon X
                    ->modalWidth('7xl'),
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
            'index' => Pages\ListEducationalInstitutions::route('/'),
            'create' => Pages\CreateEducationalInstitution::route('/create'),
            'edit' => Pages\EditEducationalInstitution::route('/{record}/edit'),
        ];
    }
}