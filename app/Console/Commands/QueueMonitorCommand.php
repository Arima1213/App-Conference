<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QueueMonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:monitor {--check : Check current queue status} {--restart : Restart failed jobs} {--cleanup : Clean old jobs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor and manage queue jobs for cPanel deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Queue Monitor - PPPKMI Conference System');
        $this->info('============================================');
        $this->newLine();

        if ($this->option('check')) {
            $this->checkQueueStatus();
        } elseif ($this->option('restart')) {
            $this->restartFailedJobs();
        } elseif ($this->option('cleanup')) {
            $this->cleanupOldJobs();
        } else {
            $this->performFullMonitoring();
        }

        return 0;
    }

    /**
     * Check current queue status
     */
    private function checkQueueStatus()
    {
        $this->info('📊 Current Queue Status:');

        // Pending Jobs
        $pendingJobs = DB::table('jobs')->count();
        $this->line("📋 Pending Jobs: {$pendingJobs}");

        // Failed Jobs
        $failedJobs = DB::table('failed_jobs')->count();
        if ($failedJobs > 0) {
            $this->error("❌ Failed Jobs: {$failedJobs}");
        } else {
            $this->line("✅ Failed Jobs: {$failedJobs}");
        }

        // Recent Job Batches
        $recentBatches = DB::table('job_batches')
            ->where('created_at', '>', now()->subHours(24)->timestamp)
            ->count();
        $this->line("📦 Recent Batches (24h): {$recentBatches}");

        // Queue Health Check
        $lastJob = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastJob) {
            $lastJobTime = Carbon::createFromTimestamp($lastJob->created_at);
            $minutesAgo = $lastJobTime->diffInMinutes(now());

            if ($minutesAgo > 60) {
                $this->warn("⚠️ Last job was {$minutesAgo} minutes ago - Queue may be stuck");
            } else {
                $this->line("⏰ Last job: {$minutesAgo} minutes ago");
            }
        } else {
            $this->line("📭 No jobs in queue");
        }

        $this->newLine();
    }

    /**
     * Restart failed jobs
     */
    private function restartFailedJobs()
    {
        $this->info('🔄 Restarting Failed Jobs...');

        $failedJobs = DB::table('failed_jobs')->get();

        if ($failedJobs->isEmpty()) {
            $this->line("✅ No failed jobs to restart");
            return;
        }

        foreach ($failedJobs as $job) {
            try {
                // Re-queue the failed job
                $this->call('queue:retry', ['id' => $job->uuid]);
                $this->line("✅ Restarted job: {$job->uuid}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to restart job {$job->uuid}: " . $e->getMessage());
            }
        }

        $this->info("🎉 Failed jobs restart process completed");
        $this->newLine();
    }

    /**
     * Clean up old jobs
     */
    private function cleanupOldJobs()
    {
        $this->info('🧹 Cleaning Up Old Jobs...');

        // Clean failed jobs older than 7 days
        $oldFailedJobs = DB::table('failed_jobs')
            ->where('failed_at', '<', now()->subDays(7))
            ->count();

        if ($oldFailedJobs > 0) {
            DB::table('failed_jobs')
                ->where('failed_at', '<', now()->subDays(7))
                ->delete();
            $this->line("🗑️ Cleaned {$oldFailedJobs} old failed jobs");
        }

        // Clean old job batches
        $oldBatches = DB::table('job_batches')
            ->where('created_at', '<', now()->subDays(30)->timestamp)
            ->count();

        if ($oldBatches > 0) {
            DB::table('job_batches')
                ->where('created_at', '<', now()->subDays(30)->timestamp)
                ->delete();
            $this->line("🗑️ Cleaned {$oldBatches} old job batches");
        }

        $this->info("✨ Cleanup completed");
        $this->newLine();
    }

    /**
     * Perform full monitoring
     */
    private function performFullMonitoring()
    {
        $this->checkQueueStatus();

        // Log queue metrics for dashboard
        $this->logQueueMetrics();

        $this->info('🎯 Queue monitoring completed. Check the dashboard for detailed metrics.');
    }

    /**
     * Log queue metrics for dashboard consumption
     */
    private function logQueueMetrics()
    {
        $metrics = [
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'completed_jobs_today' => DB::table('job_batches')
                ->where('created_at', '>', now()->startOfDay()->timestamp)
                ->sum('total_jobs'),
            'checked_at' => now()->toISOString(),
        ];

        // Store metrics in cache for dashboard widget
        cache(['queue_metrics' => $metrics], now()->addMinutes(5));

        Log::info('Queue metrics updated', $metrics);
    }
}
