<?php

namespace App\Filament\Participant\Resources;

use App\Filament\Participant\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('seminar_fee_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('participant_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('invoice_code')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('paid_at'),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('va_number')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('participant.conference.title')
                    ->label('Conference Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Invoice Code')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount (IDR)')
                    ->numeric()
                    ->sortable()
                    ->money('IDR', true),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Payment Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('current_user')
                    ->query(fn(Builder $query) => $query->where('participant_id', Auth::user()->id))
                    ->hidden()
                    ->default(true),
            ])
            ->actions([
                Tables\Actions\Action::make('pay')
                    ->label('Pay')
                    ->url(fn(Payment $record) => route('filament.participant.pages.payment-page', [
                        'payment' => encrypt($record->id),
                        'participant' => $record->participant_id,

                    ]))
                    ->icon('heroicon-o-credit-card')
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}