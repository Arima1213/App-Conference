<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Add New Participant',
                    'url' => route('filament.manage.resources.participants.create'),
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'primary',
                ],
                [
                    'label' => 'Check Attendance',
                    'url' => route('filament.manage.resources.attendance-logs.index'),
                    'icon' => 'heroicon-o-qr-code',
                    'color' => 'success',
                ],
                [
                    'label' => 'Payment Report',
                    'url' => route('filament.manage.resources.payments.index'),
                    'icon' => 'heroicon-o-banknotes',
                    'color' => 'warning',
                ],
                [
                    'label' => 'Manage Sponsors',
                    'url' => route('filament.manage.resources.sponsors.index'),
                    'icon' => 'heroicon-o-building-office-2',
                    'color' => 'info',
                ],
            ],
        ];
    }
}
