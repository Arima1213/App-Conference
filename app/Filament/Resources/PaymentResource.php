<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('seminar_fee_id')
                    ->label('Seminar Fee')
                    ->relationship('seminarFee', 'category')
                    ->searchable()
                    ->required()
                    ->preload(),
                Forms\Components\Select::make('participant_id')
                    ->label('Participant')
                    ->relationship('participant.user', 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('invoice_code')
                    ->label('Invoice Code')
                    ->unique(ignoreRecord: true)
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('IDR')
                    ->required()
                    ->step(0.01),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Paid At')
                    ->seconds(false)
                    ->displayFormat('Y-m-d H:i')
                    ->nullable(),
                Forms\Components\Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->label('Payment Method')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('va_number')
                    ->label('Virtual Account Number')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('seminarFee.category')
                    ->label('Seminar Fee')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('participant.user.name')
                    ->label('Participant')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Invoice Code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            default => ucfirst($state),
                        };
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('va_number')
                    ->label('Virtual Account Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
        ];
    }
}