<?php

namespace App\Filament\Participant\Resources\ParticipantProfileWidgetResource\Widgets;

use App\Models\Participant;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ParticipantProfileWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.participant-profile-widget-resource.widgets.participant-profile-widget';

    protected static ?int $sort = 1; // urutan di dashboard
    protected int | string | array $columnSpan = [
        'md' => 1, // lebar penuh di desktop/tablet
        'default' => 2, // lebar 2 kolom di mobile
    ];

    public function getViewData(): array
    {
        $participant = Participant::where('user_id', Auth::id())->first();

        return compact('participant');
    }
}