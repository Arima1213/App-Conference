<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CpanelQueueSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpanel:setup-queue {--generate-cron : Generate cron job commands} {--check-env : Check environment for cPanel deployment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup and manage queue system for cPanel deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏗️  cPanel Queue Setup - PPPKMI Conference');
        $this->info('=====================================');
        $this->newLine();

        if ($this->option('generate-cron')) {
            $this->generateCronCommands();
        } elseif ($this->option('check-env')) {
            $this->checkEnvironment();
        } else {
            $this->performFullSetup();
        }

        return 0;
    }

    /**
     * Generate cron job commands for cPanel
     */
    private function generateCronCommands()
    {
        $this->info('📋 Generating cPanel Cron Job Commands:');
        $this->newLine();

        $basePath = '/home/pppkmior/public_html/conference';
        $phpPath = '/usr/local/bin/ea-php82'; // cPanel PHP path format

        $cronJobs = [
            [
                'schedule' => '* * * * *',
                'command' => "cd {$basePath} && {$phpPath} artisan schedule:run >> storage/logs/scheduler.log 2>&1",
                'description' => 'Laravel Scheduler (Main - Required)',
            ],
            [
                'schedule' => '*/5 * * * *',
                'command' => "cd {$basePath} && {$phpPath} artisan queue:work --stop-when-empty --timeout=300 >> storage/logs/queue-worker.log 2>&1",
                'description' => 'Queue Worker (Backup - Optional)',
            ],
            [
                'schedule' => '0 2 * * *',
                'command' => "cd {$basePath} && {$phpPath} artisan queue:monitor --cleanup >> storage/logs/cleanup.log 2>&1",
                'description' => 'Daily Cleanup (Recommended)',
            ],
        ];
        foreach ($cronJobs as $job) {
            $this->line("🕐 {$job['description']}");
            $this->line("   Schedule: {$job['schedule']}");
            $this->line("   Command:  {$job['command']}");
            $this->newLine();
        }

        $this->warn('⚠️  Important Notes:');
        $this->line('1. PHP version used: ea-php82 (check MultiPHP Manager for your domain)');
        $this->line('2. Path configured for: /home/pppkmior/public_html/conference');
        $this->line('3. Test commands manually before adding to cron');
        $this->line('4. Monitor logs after deployment');
        $this->line('5. Adjust PHP version if needed (ea-php81, ea-php83, etc.)');
        $this->newLine();
    }

    /**
     * Check environment for cPanel deployment
     */
    private function checkEnvironment()
    {
        $this->info('🔍 Environment Check for cPanel Deployment:');
        $this->newLine();

        // Check .env settings
        $this->info('📁 Environment Configuration:');
        $this->checkEnvSetting('QUEUE_CONNECTION', 'database', 'Queue driver');
        $this->checkEnvSetting('QUEUE_FAILED_DRIVER', 'database-uuids', 'Failed jobs driver');
        $this->checkEnvSetting('LOG_CHANNEL', ['daily', 'single'], 'Logging channel');
        $this->checkEnvSetting('APP_ENV', 'production', 'Application environment');
        $this->newLine();

        // Check database tables
        $this->info('🗄️ Database Tables:');
        $this->checkDatabaseTable('jobs', 'Queue jobs storage');
        $this->checkDatabaseTable('failed_jobs', 'Failed jobs storage');
        $this->checkDatabaseTable('job_batches', 'Job batches storage');
        $this->newLine();

        // Check storage permissions
        $this->info('📂 Storage & Permissions:');
        $this->checkStoragePermissions();
        $this->newLine();

        // Check artisan availability
        $this->info('⚙️ Artisan Commands:');
        $this->checkArtisanCommands();
        $this->newLine();
    }

    /**
     * Perform full setup
     */
    private function performFullSetup()
    {
        $this->checkEnvironment();
        $this->generateCronCommands();

        $this->info('🎯 Setup Complete! Next Steps:');
        $this->line('1. Copy cron job commands to cPanel');
        $this->line('2. Update paths with your actual cPanel username');
        $this->line('3. Test queue:work manually first');
        $this->line('4. Monitor dashboard widgets for queue health');
        $this->line('5. Check logs regularly in storage/logs/');
        $this->newLine();
    }

    /**
     * Check environment setting
     */
    private function checkEnvSetting(string $key, $expected, string $description)
    {
        $value = config(strtolower(str_replace('_', '.', $key)));
        $envValue = env($key);

        if (is_array($expected)) {
            $status = in_array($value, $expected) ? '✅' : '❌';
            $expectedStr = implode(' or ', $expected);
        } else {
            $status = $value === $expected ? '✅' : '❌';
            $expectedStr = $expected;
        }

        $this->line("{$status} {$description}: {$value} (expected: {$expectedStr})");
    }

    /**
     * Check database table exists
     */
    private function checkDatabaseTable(string $table, string $description)
    {
        try {
            $exists = Schema::hasTable($table);
            $status = $exists ? '✅' : '❌';
            $this->line("{$status} {$description}: {$table}");

            if ($exists && $table === 'jobs') {
                $count = DB::table($table)->count();
                $this->line("   📊 Current jobs count: {$count}");
            }
        } catch (\Exception $e) {
            $this->line("❌ {$description}: Error checking table - {$e->getMessage()}");
        }
    }

    /**
     * Check storage permissions
     */
    private function checkStoragePermissions()
    {
        $paths = [
            'storage/logs' => 'Log files directory',
            'storage/app' => 'Application storage',
            'storage/framework/cache' => 'Cache directory',
        ];

        foreach ($paths as $path => $description) {
            $fullPath = base_path($path);
            $writable = is_writable($fullPath);
            $exists = file_exists($fullPath);

            if (!$exists) {
                $this->line("❌ {$description}: Directory doesn't exist ({$path})");
            } elseif (!$writable) {
                $this->line("⚠️ {$description}: Not writable ({$path})");
            } else {
                $this->line("✅ {$description}: OK ({$path})");
            }
        }
    }

    /**
     * Check artisan commands availability
     */
    private function checkArtisanCommands()
    {
        $commands = [
            'queue:work' => 'Queue worker',
            'queue:monitor' => 'Queue monitoring',
            'schedule:run' => 'Laravel scheduler',
            'schedule:list' => 'Scheduled tasks list',
        ];

        foreach ($commands as $command => $description) {
            try {
                $result = Artisan::call($command, ['--help' => true]);
                $status = $result === 0 ? '✅' : '❌';
                $this->line("{$status} {$description}: php artisan {$command}");
            } catch (\Exception $e) {
                $this->line("❌ {$description}: Command not available - {$e->getMessage()}");
            }
        }
    }
}
