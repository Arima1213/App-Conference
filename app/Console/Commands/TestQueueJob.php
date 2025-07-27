<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantRegistrationMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TestQueueJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:queue-job {--count=3 : Number of test jobs to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test queue jobs for monitoring dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');

        $this->info("🧪 Creating {$count} test queue jobs...");
        $this->newLine();

        for ($i = 1; $i <= $count; $i++) {
            // Create different types of jobs
            $this->createTestEmailJob($i);

            if ($i % 2 === 0) {
                $this->createTestNotificationJob($i);
            }

            $this->line("✅ Created test job #{$i}");
        }

        $this->newLine();
        $this->info("🎉 Successfully created {$count} test queue jobs!");
        $this->line("📊 Check the dashboard for queue monitoring updates");
        $this->line("🔍 Run 'php artisan queue:monitor --check' to see current status");

        return 0;
    }

    /**
     * Create test email job
     */
    private function createTestEmailJob(int $index): void
    {
        // Create a simple mail job using raw content
        dispatch(function () use ($index) {
            Mail::raw("Test Queue Job #{$index} - This is a test email job created for queue monitoring demonstration.", function ($message) use ($index) {
                $message->to('test@example.com')
                    ->subject("Test Queue Email #{$index} - PPPKMI Conference");
            });
        })->delay(now()->addSeconds($index * 2));
    }

    /**
     * Create test notification job
     */
    private function createTestNotificationJob(int $index): void
    {
        // Create a test job using dispatch
        dispatch(function () use ($index) {
            // Simulate some processing
            sleep(1);
            Log::info("Test queue job #{$index} processed successfully");
        })->delay(now()->addSeconds($index * 5));
    }
}
