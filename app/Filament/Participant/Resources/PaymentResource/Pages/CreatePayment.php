<?php

namespace App\Filament\Participant\Resources\PaymentResource\Pages;

use App\Filament\Participant\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
