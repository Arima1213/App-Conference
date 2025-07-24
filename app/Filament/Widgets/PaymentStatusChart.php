<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payment;

class PaymentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Status Overview';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $paidCount = Payment::where('payment_status', 'paid')->count();
        $pendingCount = Payment::where('payment_status', 'pending')->count();
        $failedCount = Payment::where('payment_status', 'failed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Payment Status',
                    'data' => [$paidCount, $pendingCount, $failedCount],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',  // Green for paid
                        'rgb(251, 191, 36)', // Yellow for pending
                        'rgb(239, 68, 68)',  // Red for failed
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                    ],
                ],
            ],
            'labels' => ['Paid', 'Pending', 'Failed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
