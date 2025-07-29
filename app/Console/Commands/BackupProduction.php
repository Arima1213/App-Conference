<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'production:backup {--type=full : Type of backup (full, database, files)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create production backup for PPPKMI Conference';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        $this->info('🔄 PPPKMI Conference - Production Backup');
        $this->info('=====================================');
        $this->newLine();

        switch ($type) {
            case 'database':
                $this->backupDatabase($timestamp);
                break;
            case 'files':
                $this->backupFiles($timestamp);
                break;
            case 'full':
            default:
                $this->backupDatabase($timestamp);
                $this->backupFiles($timestamp);
                break;
        }

        $this->cleanupOldBackups();

        $this->newLine();
        $this->info('✅ Backup completed successfully!');

        return 0;
    }

    /**
     * Backup database
     */
    private function backupDatabase($timestamp)
    {
        $this->info('📄 Creating database backup...');

        $backupPath = storage_path("app/backups/database");

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = "pppkmi_conference_db_{$timestamp}.sql";
        $filepath = "{$backupPath}/{$filename}";

        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Create mysqldump command
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbName),
            escapeshellarg($filepath)
        );

        $result = shell_exec($command);

        if (file_exists($filepath) && filesize($filepath) > 0) {
            $size = $this->formatBytes(filesize($filepath));
            $this->line("✅ Database backup created: {$filename} ({$size})");

            // Compress the backup
            $this->compressFile($filepath);
        } else {
            $this->error("❌ Database backup failed");
        }
    }

    /**
     * Backup important files
     */
    private function backupFiles($timestamp)
    {
        $this->info('📁 Creating files backup...');

        $backupPath = storage_path("app/backups/files");

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = "pppkmi_conference_files_{$timestamp}.tar.gz";
        $filepath = "{$backupPath}/{$filename}";

        // Files and directories to backup
        $includePaths = [
            '.env',
            'storage/app/public',
            'public/uploads',
            'public/excel-templates',
            'public/assets',
            'config/',
            'resources/views',
            'app/',
            'database/migrations',
            'database/seeders',
            'routes/',
        ];

        // Create tar command
        $basePath = base_path();
        $includeList = implode(' ', array_map('escapeshellarg', $includePaths));

        $command = sprintf(
            'cd %s && tar -czf %s %s 2>/dev/null',
            escapeshellarg($basePath),
            escapeshellarg($filepath),
            $includeList
        );

        shell_exec($command);

        if (file_exists($filepath) && filesize($filepath) > 0) {
            $size = $this->formatBytes(filesize($filepath));
            $this->line("✅ Files backup created: {$filename} ({$size})");
        } else {
            $this->error("❌ Files backup failed");
        }
    }

    /**
     * Compress file using gzip
     */
    private function compressFile($filepath)
    {
        $compressedPath = $filepath . '.gz';

        $command = sprintf('gzip %s', escapeshellarg($filepath));
        shell_exec($command);

        if (file_exists($compressedPath)) {
            $size = $this->formatBytes(filesize($compressedPath));
            $this->line("🗜️ Compressed: " . basename($compressedPath) . " ({$size})");
        }
    }

    /**
     * Clean up old backups (keep last 7 days)
     */
    private function cleanupOldBackups()
    {
        $this->info('🧹 Cleaning up old backups...');

        $backupPaths = [
            storage_path('app/backups/database'),
            storage_path('app/backups/files'),
        ];

        $cutoffDate = Carbon::now()->subDays(7);
        $deletedCount = 0;

        foreach ($backupPaths as $path) {
            if (!file_exists($path)) continue;

            $files = glob($path . '/*');

            foreach ($files as $file) {
                $fileDate = Carbon::createFromTimestamp(filemtime($file));

                if ($fileDate->lt($cutoffDate)) {
                    unlink($file);
                    $deletedCount++;
                    $this->line("🗑️ Deleted old backup: " . basename($file));
                }
            }
        }

        if ($deletedCount === 0) {
            $this->line("✅ No old backups to clean up");
        } else {
            $this->line("✅ Cleaned up {$deletedCount} old backup(s)");
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
