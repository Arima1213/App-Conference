<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestAllCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all custom commands are registered properly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Custom Commands Registration');
        $this->info('=====================================');
        $this->newLine();

        $commands = [
            'storage:link-cpanel' => 'Create storage link for cPanel',
            'queue:monitor' => 'Monitor queue health',
            'production:validate' => 'Validate production setup',
            'production:backup' => 'Create production backup',
            'payments:cleanup-expired' => 'Cleanup expired payments',
            'cpanel:queue-setup' => 'Setup queue for cPanel',
        ];

        $registered = 0;
        $total = count($commands);

        foreach ($commands as $command => $description) {
            if ($this->commandExists($command)) {
                $this->line("✅ {$command} - {$description}");
                $registered++;
            } else {
                $this->line("❌ {$command} - NOT REGISTERED");
            }
        }

        $this->newLine();
        $this->info("📊 Commands Status: {$registered}/{$total} registered");

        if ($registered === $total) {
            $this->info("🎉 All commands are registered correctly!");
        } else {
            $this->error("⚠️ Some commands are missing. Check AppServiceProvider registration.");
        }

        return $registered === $total ? 0 : 1;
    }

    /**
     * Check if command exists
     */
    private function commandExists($command)
    {
        try {
            $output = $this->getApplication()->find($command);
            return $output !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}
