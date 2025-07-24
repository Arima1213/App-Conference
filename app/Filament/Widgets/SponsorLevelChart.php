<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sponsor;

class SponsorLevelChart extends ChartWidget
{
    protected static ?string $heading = 'Sponsor Distribution by Level';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $goldCount = Sponsor::where('level', 'gold')->count();
        $silverCount = Sponsor::where('level', 'silver')->count();
        $bronzeCount = Sponsor::where('level', 'bronze')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Sponsors by Level',
                    'data' => [$goldCount, $silverCount, $bronzeCount],
                    'backgroundColor' => [
                        'rgb(255, 215, 0)',   // Gold
                        'rgb(192, 192, 192)', // Silver
                        'rgb(205, 127, 50)',  // Bronze
                    ],
                    'borderColor' => [
                        'rgb(255, 215, 0)',
                        'rgb(192, 192, 192)',
                        'rgb(205, 127, 50)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Gold', 'Silver', 'Bronze'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
