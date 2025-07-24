<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Conference;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;

class SystemHealthWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeConferences = Conference::where('is_active', true)->count();
        $databaseSize = $this->getDatabaseSize();
        $todayRegistrations = User::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('System users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Conferences', $activeConferences)
                ->description('Currently running')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make('Database Size', $databaseSize)
                ->description('Storage usage')
                ->descriptionIcon('heroicon-m-server-stack')
                ->color('info'),

            Stat::make('Today Registrations', $todayRegistrations)
                ->description('New user signups')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
        ];
    }

    private function getDatabaseSize(): string
    {
        try {
            $size = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
            ");

            return ($size[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
