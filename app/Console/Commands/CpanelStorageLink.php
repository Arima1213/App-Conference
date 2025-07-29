<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CpanelStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-cpanel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create storage link for cPanel hosting without exec() function';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔗 Creating storage link for cPanel...');

        $target = storage_path('app/public');
        $link = public_path('storage');

        // Check if target directory exists
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
            $this->info("✅ Created target directory: {$target}");
        }

        // Remove existing link if it exists
        if (File::exists($link)) {
            if (is_link($link)) {
                unlink($link);
                $this->info("🗑️ Removed existing symlink");
            } elseif (File::isDirectory($link)) {
                File::deleteDirectory($link);
                $this->info("🗑️ Removed existing directory");
            } else {
                unlink($link);
                $this->info("🗑️ Removed existing file");
            }
        }

        // Try to create symlink without exec()
        if ($this->createStorageLink($target, $link)) {
            $this->info("✅ Storage link created successfully!");
            $this->info("   Target: {$target}");
            $this->info("   Link: {$link}");
        } else {
            $this->error("❌ Failed to create storage link");
            $this->createManualStorageSetup();
        }

        return 0;
    }

    /**
     * Create storage link without using exec()
     */
    private function createStorageLink($target, $link)
    {
        try {
            // Method 1: Try native symlink function
            if (function_exists('symlink') && !windows_os()) {
                return symlink($target, $link);
            }

            // Method 2: Try creating with relative path
            $relativePath = $this->getRelativePath(dirname($link), $target);
            if (function_exists('symlink')) {
                return symlink($relativePath, $link);
            }

            // Method 3: Copy method (fallback)
            $this->warn("⚠️ Symlink not available, using copy method");
            return $this->copyStorageFiles($target, $link);
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get relative path between two directories
     */
    private function getRelativePath($from, $to)
    {
        $from = rtrim(str_replace('\\', '/', realpath($from)), '/');
        $to = rtrim(str_replace('\\', '/', realpath($to)), '/');

        $fromParts = explode('/', $from);
        $toParts = explode('/', $to);

        $common = [];
        for ($i = 0; $i < min(count($fromParts), count($toParts)); $i++) {
            if ($fromParts[$i] === $toParts[$i]) {
                $common[] = $fromParts[$i];
            } else {
                break;
            }
        }

        $upLevels = count($fromParts) - count($common);
        $relativeParts = array_fill(0, $upLevels, '..');
        $relativeParts = array_merge($relativeParts, array_slice($toParts, count($common)));

        return implode('/', $relativeParts);
    }

    /**
     * Copy storage files as fallback
     */
    private function copyStorageFiles($target, $link)
    {
        try {
            // Create directory structure
            File::makeDirectory($link, 0755, true);

            // Copy files
            if (File::exists($target)) {
                File::copyDirectory($target, $link);
            }

            // Create .htaccess for direct access
            $htaccessContent = <<<'EOT'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ ../storage/app/public/$1 [L]
</IfModule>
EOT;

            File::put($link . '/.htaccess', $htaccessContent);

            $this->warn("📁 Using copy method instead of symlink");
            $this->info("   Note: Files will need manual sync when storage changes");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create manual storage setup instructions
     */
    private function createManualStorageSetup()
    {
        $this->error("🚨 Automatic storage link failed!");
        $this->newLine();
        $this->info("📝 Manual setup required:");
        $this->info("1. Via cPanel File Manager:");
        $this->info("   - Navigate to public_html/conference/public/");
        $this->info("   - Create folder named 'storage'");
        $this->info("   - Copy contents from storage/app/public/ to public/storage/");
        $this->newLine();
        $this->info("2. Via SSH (if available):");
        $this->info("   cd /home/pppkmior/public_html/conference");
        $this->info("   ln -s ../storage/app/public public/storage");
        $this->newLine();
        $this->info("3. Contact hosting support to enable symlink function");
    }
}
