<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Sponsor;
use App\Models\AttendanceLog;

class ConferenceOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Participants', Participant::count())
                ->description('Registered participants')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Verified Participants', Participant::where('status', 'verified')->count())
                ->description('Ready to attend')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Revenue', 'Rp ' . number_format(Payment::where('payment_status', 'paid')->sum('amount'), 0, ',', '.'))
                ->description('From paid registrations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Sponsors', Sponsor::count())
                ->description('Partnership count')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),
        ];
    }
}
