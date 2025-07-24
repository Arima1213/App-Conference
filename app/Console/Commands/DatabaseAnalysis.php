<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Sponsor;
use App\Models\AttendanceLog;
use App\Models\Speaker;
use App\Models\Schedule;
use App\Models\EducationalInstitution;
use App\Models\Conference;

class DatabaseAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze database for dashboard widgets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Conference App Database Analysis');
        $this->info('================================');
        $this->newLine();

        // Participants Analysis
        $this->info('1. PARTICIPANTS OVERVIEW:');
        $totalParticipants = Participant::count();
        $verifiedParticipants = Participant::where('status', 'verified')->count();
        $arrivedParticipants = Participant::where('status', 'arrived')->count();
        $unverifiedParticipants = Participant::where('status', 'unverified')->count();
        $recentParticipants = Participant::where('created_at', '>=', now()->subDays(7))->count();

        $this->line("Total Participants: {$totalParticipants}");
        $this->line("Verified Participants: {$verifiedParticipants}");
        $this->line("Arrived Participants: {$arrivedParticipants}");
        $this->line("Unverified Participants: {$unverifiedParticipants}");
        $this->line("New This Week: {$recentParticipants}");
        $this->newLine();

        // Payment Analysis
        $this->info('2. PAYMENT OVERVIEW:');
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $pendingPayments = Payment::where('payment_status', 'pending')->count();
        $failedPayments = Payment::where('payment_status', 'failed')->count();
        $paidPayments = Payment::where('payment_status', 'paid')->count();

        $this->line("Total Revenue: Rp " . number_format($totalRevenue, 0, ',', '.'));
        $this->line("Paid Payments: {$paidPayments}");
        $this->line("Pending Payments: {$pendingPayments}");
        $this->line("Failed Payments: {$failedPayments}");
        $this->newLine();

        // Sponsors Analysis
        $this->info('3. SPONSORS OVERVIEW:');
        $totalSponsors = Sponsor::count();
        $goldSponsors = Sponsor::where('level', 'gold')->count();
        $silverSponsors = Sponsor::where('level', 'silver')->count();
        $bronzeSponsors = Sponsor::where('level', 'bronze')->count();

        $this->line("Total Sponsors: {$totalSponsors}");
        $this->line("Gold Sponsors: {$goldSponsors}");
        $this->line("Silver Sponsors: {$silverSponsors}");
        $this->line("Bronze Sponsors: {$bronzeSponsors}");
        $this->newLine();

        // Attendance Analysis
        $this->info('4. ATTENDANCE OVERVIEW:');
        $totalAttendance = AttendanceLog::count();
        $uniqueAttendees = AttendanceLog::distinct('participant_id')->count();
        $todayAttendance = AttendanceLog::whereDate('created_at', today())->count();

        $this->line("Total Check-ins: {$totalAttendance}");
        $this->line("Unique Attendees: {$uniqueAttendees}");
        $this->line("Today Check-ins: {$todayAttendance}");
        $this->newLine();

        // Educational Institutions
        $this->info('5. INSTITUTIONS OVERVIEW:');
        $totalInstitutions = EducationalInstitution::count();

        $this->line("Total Institutions: {$totalInstitutions}");
        $this->newLine();

        // Speakers & Schedules
        $this->info('6. EVENT OVERVIEW:');
        $totalSpeakers = Speaker::count();
        $totalSchedules = Schedule::count();
        $upcomingSchedules = Schedule::where('start_time', '>', now())->count();

        $this->line("Total Speakers: {$totalSpeakers}");
        $this->line("Total Schedules: {$totalSchedules}");
        $this->line("Upcoming Sessions: {$upcomingSchedules}");
        $this->newLine();

        // Conferences
        $this->info('7. CONFERENCES OVERVIEW:');
        $totalConferences = Conference::count();
        $activeConferences = Conference::where('is_active', true)->count();

        $this->line("Total Conferences: {$totalConferences}");
        $this->line("Active Conferences: {$activeConferences}");
    }
}
