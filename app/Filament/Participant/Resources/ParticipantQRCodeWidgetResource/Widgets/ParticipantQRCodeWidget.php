<?php

namespace App\Filament\Participant\Resources\ParticipantQRCodeWidgetResource\Widgets;

use App\Models\Participant;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ParticipantQRCodeWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.participant-q-r-code-widget-resource.widgets.participant-q-r-code-widget';
    protected static ?int $sort = 1; // urutan di dashboard
    protected int | string | array $columnSpan = 1; // lebar penuh
    public ?string $qrCodeUrl = null;
    public ?Participant $participant = null;

    public function mount(): void
    {
        $this->participant = Participant::where('user_id', Auth::id())->latest()->first();

        if ($this->participant) {
            $encrypted = Crypt::encryptString($this->participant->id);

            // Generate QR URL menuju route verifikasi atau tampilan identitas
            $qrDataUrl = route('participant.qr.show', ['encrypted' => $encrypted]);

            // QR Code dari API
            $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrDataUrl) . '&size=200x200';
        }
    }
}