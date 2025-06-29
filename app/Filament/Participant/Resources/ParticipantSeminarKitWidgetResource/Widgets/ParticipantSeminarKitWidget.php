<?php

namespace App\Filament\Participant\Resources\ParticipantSeminarKitWidgetResource\Widgets;

use App\Models\Participant;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ParticipantSeminarKitWidget extends Widget
{
    protected static string $view = 'filament.participant.resources.participant-seminar-kit-widget-resource.widgets.participant-seminar-kit-widget';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'default' => 2,
    ];

    public ?string $qrCodeUrl = null;
    public ?Participant $participant = null;

    public function mount(): void
    {
        $this->participant = Participant::where('user_id', Auth::id())->latest()->first();

        if ($this->participant) {
            // Cek apakah ada pembayaran yang status-nya 'paid'
            $payment = $this->participant->payment()
                ->where('payment_status', 'paid')
                ->latest()
                ->first();

            if ($payment) {
                $encrypted = Crypt::encryptString($this->participant->id);
                $qrDataUrl = route('participant.qr.seminar-kit', ['encrypted' => $encrypted]);
                $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrDataUrl) . '&size=200x200';
            }
        }
    }
}