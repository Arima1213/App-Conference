<?php

namespace App\Filament\Participant\Resources\RegisterConferenceeWidgetResource\Widgets;

use App\Models\Conference;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Crypt;

class RegisterConferenceeWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.register-conferencee-widget-resource.widgets.register-conferencee-widget';

    public ?Conference $conference = null;
    public ?string $encryptedConferenceId = null;
    protected int | string | array $columnSpan = 2; // lebar penuh


    public function mount(): void
    {
        $this->conference = Conference::where('is_active', true)
            ->whereHas('schedules')
            ->with(['schedules' => fn($query) => $query->orderBy('start_time')])
            ->get()
            ->sortBy(fn($conf) => optional($conf->schedules->first())->start_time)
            ->first();

        $this->encryptedConferenceId = $this->conference
            ? Crypt::encryptString($this->conference->id)
            : null;
    }

    public static function canView(): bool
    {
        // Tampilkan hanya jika ada conference aktif
        return Conference::where('is_active', true)->exists();
    }
}
