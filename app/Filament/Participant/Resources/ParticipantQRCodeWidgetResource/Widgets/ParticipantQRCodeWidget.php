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
    protected int | string | array $columnSpan = [
        'md' => 1, // lebar penuh di desktop/tablet
        'default' => 2, // lebar 2 kolom di mobile
    ];
    public ?string $qrCodeUrl = null;
    public ?Participant $participant = null;

    public function mount(): void
    {
        $this->participant = Participant::where('user_id', Auth::id())->latest()->first();

        if ($this->participant) {
            $payment = $this->participant->payment()
                ->where('payment_status', 'paid')
                ->latest()
                ->first();

            if ($payment) {
                $encrypted = Crypt::encryptString($this->participant->id);
                $qrDataUrl = route('participant.qr.show', ['encrypted' => $encrypted]);
                $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrDataUrl) . '&size=200x200';
            }
        }
    }
}