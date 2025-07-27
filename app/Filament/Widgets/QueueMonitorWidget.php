<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QueueMonitorWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        // Get cached metrics or calculate fresh ones
        $metrics = Cache::get('queue_metrics', function () {
            return [
                'pending_jobs' => DB::table('jobs')->count(),
                'failed_jobs' => DB::table('failed_jobs')->count(),
                'completed_jobs_today' => DB::table('job_batches')
                    ->where('created_at', '>', now()->startOfDay()->timestamp)
                    ->sum('total_jobs'),
                'checked_at' => now()->toISOString(),
            ];
        });

        // Calculate queue health status
        $queueHealth = $this->calculateQueueHealth();
        $healthColor = $this->getHealthColor($queueHealth);
        $healthIcon = $this->getHealthIcon($queueHealth);

        return [
            Stat::make('Pending Jobs', $metrics['pending_jobs'])
                ->description('Jobs waiting in queue')
                ->descriptionIcon('heroicon-m-clock')
                ->color($metrics['pending_jobs'] > 50 ? 'warning' : 'primary'),

            Stat::make('Failed Jobs', $metrics['failed_jobs'])
                ->description('Jobs that failed processing')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($metrics['failed_jobs'] > 0 ? 'danger' : 'success'),

            Stat::make('Jobs Today', $metrics['completed_jobs_today'] ?? 0)
                ->description('Jobs processed today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Queue Health', $queueHealth . '%')
                ->description('Overall queue performance')
                ->descriptionIcon($healthIcon)
                ->color($healthColor),

            Stat::make('Email Queue', $this->getEmailQueueCount())
                ->description('Email jobs in queue')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),

            Stat::make('Last Check', $this->getLastCheckTime())
                ->description('Queue monitoring status')
                ->descriptionIcon('heroicon-m-eye')
                ->color('gray'),
        ];
    }

    private function calculateQueueHealth(): int
    {
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();

        // Simple health calculation
        if ($failedJobs > 10) return 25; // Poor health
        if ($failedJobs > 5) return 50;  // Fair health
        if ($pendingJobs > 100) return 75; // Good health but high load

        return 100; // Excellent health
    }

    private function getHealthColor(int $health): string
    {
        return match (true) {
            $health >= 90 => 'success',
            $health >= 70 => 'warning',
            default => 'danger',
        };
    }

    private function getHealthIcon(int $health): string
    {
        return match (true) {
            $health >= 90 => 'heroicon-m-heart',
            $health >= 70 => 'heroicon-m-exclamation-triangle',
            default => 'heroicon-m-x-circle',
        };
    }

    private function getEmailQueueCount(): int
    {
        return DB::table('jobs')
            ->where('payload', 'like', '%mail%')
            ->orWhere('payload', 'like', '%email%')
            ->count();
    }

    private function getLastCheckTime(): string
    {
        $metrics = Cache::get('queue_metrics');

        if (!$metrics || !isset($metrics['checked_at'])) {
            return 'Never';
        }

        return \Carbon\Carbon::parse($metrics['checked_at'])->diffForHumans();
    }

    public function getPollingInterval(): ?string
    {
        return '30s'; // Refresh every 30 seconds
    }
}
