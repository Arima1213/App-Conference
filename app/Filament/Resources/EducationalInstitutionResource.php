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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lembaga')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('kelompok_koordinator')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('npsn')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nama_pt')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nm_bp')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('provinsi_pt')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('jln')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('kec_pt')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('kabupaten_kota')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('no_tel')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lembaga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelompok_koordinator')
                    ->searchable(),
                Tables\Columns\TextColumn::make('npsn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nm_bp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provinsi_pt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kec_pt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kabupaten_kota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_tel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
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
            'index' => Pages\ListEducationalInstitutions::route('/'),
            'create' => Pages\CreateEducationalInstitution::route('/create'),
            'edit' => Pages\EditEducationalInstitution::route('/{record}/edit'),
        ];
    }
}
