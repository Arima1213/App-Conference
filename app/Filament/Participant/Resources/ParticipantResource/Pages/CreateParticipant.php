<?php

namespace App\Filament\Participant\Resources\ParticipantResource\Pages;

use App\Filament\Participant\Resources\ParticipantResource;
use App\Mail\ParticipantRegistrationMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function getFormActions(): array
    {
        // Override to return an empty array, removing all form actions including the 'Create' button
        return [];
    }

    protected function getRedirectUrl(): string
    {
        $participant = $this->getRecord();

        // Redirect ke halaman payment detail
        return url('/participant/payments');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Participant successfully registered!';
    }

    protected function hasCreateAnother(): bool
    {
        return false; // hilangkan tombol 'Create & create another'
    }

    // jangan tampilkan breadcrumbs pada halaman create
    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        $request = request();
        $conferenceId = null;
        $conferenceName = '';

        // Ambil parameter dari query string, bukan dari request body
        if ($request->query->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->query('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        if ($conferenceId) {
            // Misal model Conference ada di App\Models\Conference
            $conference = \App\Models\Conference::find($conferenceId);
            if ($conference) {
                $conferenceName = $conference->title;
            }
        }

        return 'Register Conference' . ($conferenceName ? ' - ' . $conferenceName : '');
    }

    // sebelum menjalankan create, lengkapi form participant code
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil conference_id dari query string
        $request = request();
        $conferenceId = null;

        if ($request->query->has('conference')) {
            try {
                $conferenceId = \Illuminate\Support\Facades\Crypt::decryptString($request->query('conference'));
            } catch (\Exception $e) {
                $conferenceId = null;
            }
        }

        $fee = '';
        if ($conferenceId) {
            $conference = \App\Models\Conference::find($conferenceId);
            if ($conference && isset($conference->fee)) {
                $fee = (string) $conference->fee;
            }
        }

        // Gunakan fee sebagai bagian dari pattern participant_code
        $participantCode = 'P' . $fee . strtoupper(uniqid());
        // dd($participantCode); // Uncomment if you want to debug

        $data['participant_code'] = $participantCode;

        return $data;
    }

    // buatkam fungsi ketika sudah tercreate maka buat data payment
    protected function afterCreate(): void
    {
        $participant = $this->getRecord();
        if ($participant) {
            try {
                // Buat data payment untuk participant
                $payment = new \App\Models\Payment();
                $payment->participant_id = $participant->id;
                $payment->seminar_fee_id = $participant->seminar_fee_id;

                // Cek apakah user sudah pernah mendaftar di conference ini
                $userId = Auth::user()->id;
                $conferenceId = $participant->conference_id;
                $hasRegistered = \App\Models\Participant::where('user_id', $userId)
                    ->exists();

                // Ambil seminar fee terkait
                $seminarFee = $participant->seminarFee;

                // Pilih amount sesuai status pendaftaran
                if ($seminarFee) {
                    $payment->amount = $hasRegistered ? $seminarFee->regular_price : $seminarFee->early_bird_price;
                } else {
                    $payment->amount = 0;
                }

                $payment->payment_status = 'pending';
                $payment->invoice_code = 'INV-' . strtoupper(uniqid());
                $payment->save();

                // Send registration email
                try {
                    // Load participant with relations for email
                    $participant->load(['user', 'conference', 'educationalInstitution', 'seminarFee']);

                    Mail::to($participant->user->email)->send(new ParticipantRegistrationMail($participant));

                    Log::info("Registration email sent successfully to: " . $participant->user->email);

                    // Notifikasi sukses dengan info email
                    \Filament\Notifications\Notification::make()
                        ->title('Registration Successful!')
                        ->body('Welcome email has been sent to ' . $participant->user->email . '. Please check your inbox and proceed to payment.')
                        ->success()
                        ->persistent()
                        ->send();
                } catch (\Exception $emailError) {
                    Log::error("Failed to send registration email: " . $emailError->getMessage());

                    // Notifikasi bahwa email gagal tapi registrasi berhasil
                    \Filament\Notifications\Notification::make()
                        ->title('Registration Successful - Email Issue')
                        ->body('Registration completed but welcome email could not be sent. Please proceed to payment.')
                        ->warning()
                        ->persistent()
                        ->send();
                }
            } catch (\Exception $e) {
                Log::error("Registration error: " . $e->getMessage());

                \Filament\Notifications\Notification::make()
                    ->title('Registration Error')
                    ->body('An error occurred during registration. Please try again or contact support.')
                    ->danger()
                    ->persistent()
                    ->send();
            }
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Failed to create participant or payment.')
                ->body('An error occurred while creating the participant or associated payment. Please try again or contact support if the issue persists.')
                ->danger()
                ->persistent()
                ->send();
        }
    }
}