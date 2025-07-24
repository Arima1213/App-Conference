<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\AttendanceLog;

class ParticipantStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalParticipants = Participant::count();
        $verifiedParticipants = Participant::where('status', 'verified')->count();
        $arrivedParticipants = Participant::where('status', 'arrived')->count();
        $attendanceRate = $totalParticipants > 0 ? round(($arrivedParticipants / $totalParticipants) * 100, 1) : 0;

        return [
            Stat::make('Unverified', Participant::where('status', 'unverified')->count())
                ->description('Awaiting verification')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Verified', $verifiedParticipants)
                ->description('Ready to attend')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Arrived', $arrivedParticipants)
                ->description($attendanceRate . '% attendance rate')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary'),

            Stat::make('New This Week', Participant::where('created_at', '>=', now()->subDays(7))->count())
                ->description('Recent registrations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
        ];
    }
}
