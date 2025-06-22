<?php

namespace App\Filament\Participant\Resources\ConferenceBannerWidgetResource\Widgets;

use App\Models\Conference;
use Filament\Widgets\Widget;

class ConferenceBannerWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.conference-banner-widget-resource.widgets.conference-banner-widget';

    protected static ?int $sort = -1; // letakkan di atas jika perlu

    protected int | string | array $columnSpan = 2; // lebar penuh

    public ?Conference $conference = null;

    public function mount(): void
    {
        $this->conference = Conference::where('is_active', true)
            ->whereHas('schedules')
            ->with(['schedules' => fn($query) => $query->orderBy('start_time')])
            ->get()
            ->sortBy(fn($conf) => optional($conf->schedules->first())->start_time)
            ->first();
    }

    public static function canView(): bool
    {
        // Tampilkan hanya jika ada conference aktif
        return Conference::where('is_active', true)->exists();
    }
}
