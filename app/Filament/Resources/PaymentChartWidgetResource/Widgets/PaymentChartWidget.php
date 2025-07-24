<?php

namespace App\Filament\Resources\PaymentChartWidgetResource\Widgets;

use Filament\Widgets\ChartWidget;

class PaymentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
