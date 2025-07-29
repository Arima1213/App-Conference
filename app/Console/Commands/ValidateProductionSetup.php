<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ValidateProductionSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'production:validate {--email= : Test email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate production configuration for PPPKMI Conference';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 PPPKMI Conference - Production Validation');
        $this->info('===========================================');
        $this->newLine();

        $this->validateEnvironment();
        $this->validateDatabase();
        $this->validateEmail();
        $this->validateMidtrans();
        $this->validateQueue();
        $this->validateStorage();

        $this->newLine();
        $this->info('🎯 Production validation completed!');
        
        return 0;
    }

    /**
     * Validate environment configuration
     */
    private function validateEnvironment()
    {
        $this->info('📁 Environment Configuration:');
        
        $checks = [
            'APP_ENV' => ['production', 'Expected: production'],
            'APP_DEBUG' => [false, 'Expected: false'],
            'APP_URL' => ['https://conference.pppkmi.org', 'Expected: HTTPS URL'],
            'LOG_LEVEL' => [['info', 'warning', 'error'], 'Expected: info/warning/error'],
        ];

        foreach ($checks as $key => $check) {
            $value = config(strtolower(str_replace('_', '.', $key)));
            $expected = $check[0];
            $description = $check[1];

            if (is_array($expected)) {
                $status = in_array($value, $expected) ? '✅' : '❌';
            } else {
                $status = $value == $expected ? '✅' : '❌';
            }

            $this->line("{$status} {$key}: {$value} ({$description})");
        }
        $this->newLine();
    }

    /**
     * Validate database connection
     */
    private function validateDatabase()
    {
        $this->info('🗄️ Database Configuration:');
        
        try {
            DB::connection()->getPdo();
            $this->line('✅ Database connection: OK');
            
            // Test database operations
            $tablesExist = [
                'users' => Schema::hasTable('users'),
                'jobs' => Schema::hasTable('jobs'),
                'failed_jobs' => Schema::hasTable('failed_jobs'),
                'participants' => Schema::hasTable('participants'),
                'payments' => Schema::hasTable('payments'),
            ];

            foreach ($tablesExist as $table => $exists) {
                $status = $exists ? '✅' : '❌';
                $this->line("{$status} Table {$table}: " . ($exists ? 'exists' : 'missing'));
            }
            
        } catch (\Exception $e) {
            $this->line('❌ Database connection: FAILED');
            $this->error('Error: ' . $e->getMessage());
        }
        $this->newLine();
    }

    /**
     * Validate email configuration
     */
    private function validateEmail()
    {
        $this->info('📧 Email Configuration:');
        
        $emailConfig = [
            'MAIL_MAILER' => config('mail.default'),
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'MAIL_PORT' => config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
            'MAIL_FROM_ADDRESS' => config('mail.from.address'),
        ];

        foreach ($emailConfig as $key => $value) {
            $status = !empty($value) ? '✅' : '❌';
            $this->line("{$status} {$key}: {$value}");
        }

        // Test email sending
        $testEmail = $this->option('email') ?? 'test@example.com';
        if ($this->confirm("Send test email to {$testEmail}?", false)) {
            try {
                Mail::raw('Test email from PPPKMI Conference production setup validation.', function ($message) use ($testEmail) {
                    $message->to($testEmail)
                        ->subject('PPPKMI Conference - Production Test Email');
                });
                $this->line('✅ Test email sent successfully');
            } catch (\Exception $e) {
                $this->line('❌ Test email failed: ' . $e->getMessage());
            }
        }
        $this->newLine();
    }

    /**
     * Validate Midtrans configuration
     */
    private function validateMidtrans()
    {
        $this->info('💳 Midtrans Configuration:');
        
        $midtransConfig = [
            'MIDTRANS_MERCHANT_ID' => config('midtrans.merchant_id'),
            'MIDTRANS_CLIENT_KEY' => config('midtrans.client_key'),
            'MIDTRANS_SERVER_KEY' => config('midtrans.server_key'),
            'MIDTRANS_IS_PRODUCTION' => config('midtrans.is_production') ? 'true' : 'false',
        ];

        foreach ($midtransConfig as $key => $value) {
            $status = !empty($value) ? '✅' : '❌';
            $displayValue = $key === 'MIDTRANS_SERVER_KEY' ? str_repeat('*', strlen($value)) : $value;
            $this->line("{$status} {$key}: {$displayValue}");
        }

        // Test Midtrans API
        if ($this->confirm('Test Midtrans API connection?', false)) {
            try {
                $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                    ->get('https://api.sandbox.midtrans.com/v2/ping');
                
                if ($response->successful()) {
                    $this->line('✅ Midtrans API connection: OK');
                } else {
                    $this->line('❌ Midtrans API connection: FAILED');
                }
            } catch (\Exception $e) {
                $this->line('❌ Midtrans API test failed: ' . $e->getMessage());
            }
        }
        $this->newLine();
    }

    /**
     * Validate queue configuration
     */
    private function validateQueue()
    {
        $this->info('🔄 Queue Configuration:');
        
        $queueConfig = [
            'QUEUE_CONNECTION' => config('queue.default'),
            'QUEUE_FAILED_DRIVER' => config('queue.failed.driver'),
        ];

        foreach ($queueConfig as $key => $value) {
            $status = !empty($value) ? '✅' : '❌';
            $this->line("{$status} {$key}: {$value}");
        }

        // Check queue tables
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            $this->line("✅ Pending jobs: {$pendingJobs}");
            $this->line("✅ Failed jobs: {$failedJobs}");
        } catch (\Exception $e) {
            $this->line('❌ Queue tables check failed: ' . $e->getMessage());
        }
        $this->newLine();
    }

    /**
     * Validate storage permissions
     */
    private function validateStorage()
    {
        $this->info('📂 Storage & Permissions:');
        
        $directories = [
            'storage/logs' => storage_path('logs'),
            'storage/app' => storage_path('app'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
        ];

        foreach ($directories as $name => $path) {
            $exists = file_exists($path);
            $writable = is_writable($path);
            
            if ($exists && $writable) {
                $this->line("✅ {$name}: OK");
            } elseif ($exists && !$writable) {
                $this->line("⚠️ {$name}: Not writable");
            } else {
                $this->line("❌ {$name}: Not found");
            }
        }
        $this->newLine();
    }
}
