<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register custom commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\CpanelStorageLink::class,
                \App\Console\Commands\QueueMonitorCommand::class,
                \App\Console\Commands\ValidateProductionSetup::class,
                \App\Console\Commands\BackupProduction::class,
                \App\Console\Commands\CleanupExpiredPayments::class,
                \App\Console\Commands\CpanelQueueSetup::class,
                \App\Console\Commands\TestAllCommands::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Schedule queue jobs for cPanel deployment
        $this->scheduleQueueJobs();
    }

    /**
     * Schedule queue management jobs
     */
    protected function scheduleQueueJobs(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Monitor queue health every 5 minutes
            $schedule->command('queue:monitor --check')
                ->everyFiveMinutes()
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/queue-monitor.log'));

            // Clean up expired payments every hour
            $schedule->command('payments:cleanup-expired')
                ->hourly()
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/cleanup.log'));

            // Process failed jobs retry every 30 minutes
            $schedule->command('queue:monitor --restart')
                ->everyThirtyMinutes()
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/queue-retry.log'));

            // Clean old logs and jobs every day at 2 AM
            $schedule->command('queue:monitor --cleanup')
                ->dailyAt('02:00')
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/cleanup.log'));

            // Daily backup at 3 AM (production only)
            if (app()->environment('production')) {
                $schedule->command('production:backup --type=full')
                    ->dailyAt('03:00')
                    ->withoutOverlapping()
                    ->runInBackground()
                    ->appendOutputTo(storage_path('logs/backup.log'));

                // Manual storage sync (fallback method)
                $schedule->call(function () {
                    $target = storage_path('app/public');
                    $link = public_path('storage');

                    if (!file_exists($link)) {
                        mkdir($link, 0755, true);
                    }

                    if (is_dir($target)) {
                        shell_exec("cp -r {$target}/* {$link}/ 2>/dev/null");
                    }
                })->weekly()->name('manual-storage-sync');
            }

            // Email queue processing (high priority)
            $schedule->command('queue:work --queue=emails,high,default --stop-when-empty --timeout=300')
                ->everyFiveMinutes()
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/email-queue.log'));

            // Regular queue processing
            $schedule->command('queue:work --stop-when-empty --timeout=300')
                ->everyTenMinutes()
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/queue-worker.log'));

            // Queue health monitoring for dashboard
            $schedule->call(function () {
                \Illuminate\Support\Facades\Artisan::call('queue:monitor', ['--check' => true]);
            })->everyMinute()->name('queue-health-dashboard');
        });
    }
}
