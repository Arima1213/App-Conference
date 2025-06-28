<?php

namespace App\Filament\Participant\Resources\RegisterConferenceeWidgetResource\Widgets;

use App\Models\Conference;
use App\Models\Participant;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RegisterConferenceeWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.register-conferencee-widget-resource.widgets.register-conferencee-widget';

    public ?Conference $conference = null;
    public ?string $encryptedConferenceId = null;
    public bool $isRegistered = false;
    protected int | string | array $columnSpan = 2; // lebar penuh

    public ?string $noActiveConferenceMessage = null;

    public function mount(): void
    {
        $this->conference = Conference::where('is_active', true)
            ->whereHas('schedules')
            ->with(['schedules' => fn($query) => $query->orderBy('start_time')])
            ->get()
            ->sortBy(fn($conf) => optional($conf->schedules->first())->start_time)
            ->first();

        if (!$this->conference) {
            $this->noActiveConferenceMessage = 'Tidak ada conference yang aktif saat ini.';
            $this->encryptedConferenceId = null;
            $this->isRegistered = false;
            return;
        }

        $this->encryptedConferenceId = Crypt::encryptString($this->conference->id);

        // Cek apakah user sudah terdaftar di conference ini
        if (Auth::check()) {
            $this->isRegistered = Participant::where('user_id', Auth::id())
                ->where('conference_id', $this->conference->id)
                ->exists();
        }
    }

    public static function canView(): bool
    {
        // Tampilkan hanya jika ada conference aktif dan user belum terdaftar
        $conference = Conference::where('is_active', true)
            ->whereHas('schedules')
            ->first();

        if (!$conference || !Auth::check()) {
            return false;
        }

        $isRegistered = Participant::where('user_id', Auth::id())
            ->where('conference_id', $conference->id)
            ->exists();

        return !$isRegistered;
    }
}