<?php

namespace App\Console\Commands;

use App\Mail\ParticipantRegistrationMail;
use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestRegistrationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-registration {participant_id?} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test registration email with actual participant data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $participantId = $this->argument('participant_id');
        $testEmail = $this->argument('email');

        try {
            if ($participantId) {
                $participant = Participant::with(['user', 'conference', 'educationalInstitution', 'seminarFee'])
                    ->find($participantId);

                if (!$participant) {
                    $this->error("Participant with ID {$participantId} not found.");
                    return 1;
                }
            } else {
                // Get latest participant for testing
                $participant = Participant::with(['user', 'conference', 'educationalInstitution', 'seminarFee'])
                    ->latest()
                    ->first();

                if (!$participant) {
                    $this->error("No participants found. Please create a participant first.");
                    return 1;
                }
            }

            $email = $testEmail ?? $participant->user->email;

            $this->info("Testing registration email...");
            $this->info("Participant: {$participant->user->name}");
            $this->info("Conference: {$participant->conference->title}");
            $this->info("Email to: {$email}");

            Mail::to($email)->send(new ParticipantRegistrationMail($participant));

            $this->info("✅ Registration email sent successfully!");
            $this->info("Check your email client or MailHog (http://localhost:8025) to view the email.");

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send registration email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());

            return 1;
        }
    }
}
