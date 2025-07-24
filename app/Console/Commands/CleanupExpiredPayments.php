<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class CleanupExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired payment tokens and mark expired payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment cleanup...');

        // Find payments with expired tokens
        $expiredTokenPayments = Payment::whereNotNull('snap_token')
            ->whereNotNull('snap_token_created_at')
            ->where('snap_token_created_at', '<', now()->subHours(24))
            ->whereIn('payment_status', ['pending', 'failed'])
            ->get();

        $this->info("Found {$expiredTokenPayments->count()} payments with expired tokens");

        // Clear expired tokens
        foreach ($expiredTokenPayments as $payment) {
            $payment->update([
                'snap_token' => null,
                'snap_token_created_at' => null,
            ]);
            $this->line("Cleared expired token for payment: {$payment->invoice_code}");
        }

        // Find very old pending payments (older than 7 days) and mark as expired
        $veryOldPayments = Payment::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->get();

        $this->info("Found {$veryOldPayments->count()} very old pending payments to expire");

        foreach ($veryOldPayments as $payment) {
            $payment->update(['payment_status' => 'expired']);
            $this->line("Marked payment as expired: {$payment->invoice_code}");
        }

        $this->success('Payment cleanup completed!');

        // Show summary
        $this->table(
            ['Status', 'Count'],
            [
                ['Tokens Cleared', $expiredTokenPayments->count()],
                ['Payments Expired', $veryOldPayments->count()],
            ]
        );

        return 0;
    }
}