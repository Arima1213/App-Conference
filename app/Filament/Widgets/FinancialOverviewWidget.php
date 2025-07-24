<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Payment;
use App\Models\Speaker;
use App\Models\Schedule;

class FinancialOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $pendingRevenue = Payment::where('payment_status', 'pending')->sum('amount');
        $averagePayment = Payment::where('payment_status', 'paid')->avg('amount') ?? 0;
        $totalSpeakers = Speaker::count();

        return [
            Stat::make('Pending Revenue', 'Rp ' . number_format($pendingRevenue, 0, ',', '.'))
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Average Payment', 'Rp ' . number_format($averagePayment, 0, ',', '.'))
                ->description('Per participant')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Total Speakers', $totalSpeakers)
                ->description('Expert presenters')
                ->descriptionIcon('heroicon-m-microphone')
                ->color('success'),

            Stat::make('Scheduled Sessions', Schedule::count())
                ->description('Conference agenda')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
        ];
    }
}
