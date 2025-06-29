<?php

namespace App\Filament\Resources\DashboardPageResource\Pages;

use Filament\Pages\Dashboard as PagesDashboard;
use App\Models\Conference;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Widgets;

class Dashboard extends PagesDashboard
{
    protected static string $view = 'filament.resources.dashboard-page-resource.pages.dashboard';

    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('conference_id')
                            ->label('Conference')
                            ->options(Conference::pluck('title', 'id'))
                            ->searchable()
                            ->placeholder('Select Conference'),
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->default(now()->endOfMonth()),
                    ])
                    ->columns(3),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Masukkan custom widget di sini jika ada
            // Contoh:
            // \App\Filament\Widgets\OverviewStats::class,
        ];
    }
}