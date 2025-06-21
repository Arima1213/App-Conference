<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeminarFeeResource\Pages;
use App\Filament\Resources\SeminarFeeResource\RelationManagers;
use App\Models\SeminarFee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeminarFeeResource extends Resource
{
    protected static ?string $model = SeminarFee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'national' => 'National',
                        'international' => 'International',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('early_bird_price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->step('0.01'),
                Forms\Components\TextInput::make('regular_price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->step('0.01'),
                Forms\Components\Select::make('currency')
                    ->options([
                        'USD' => 'USD',
                        'IDR' => 'IDR',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('early_bird_price')
                    ->sortable()
                    ->money(fn($record) => $record->currency),
                Tables\Columns\TextColumn::make('regular_price')
                    ->sortable()
                    ->money(fn($record) => $record->currency),
                Tables\Columns\TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListSeminarFees::route('/'),
            'create' => Pages\CreateSeminarFee::route('/create'),
            'edit' => Pages\EditSeminarFee::route('/{record}/edit'),
        ];
    }
}