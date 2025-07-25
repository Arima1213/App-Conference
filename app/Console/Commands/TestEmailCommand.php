<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';

        try {
            $this->info('Testing email configuration...');
            $this->info('MAIL_MAILER: ' . config('mail.default'));
            $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
            $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));

            // Test simple email
            Mail::raw('This is a test email from PPPKMI Conference system.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - PPPKMI Conference')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info("✅ Test email sent successfully to: {$email}");
            $this->info('Please check your email inbox (or MailHog if using local development).');

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Email test failed: " . $e->getMessage());
            $this->error('Please check your email configuration in .env file');

            return 1;
        }
    }
}
