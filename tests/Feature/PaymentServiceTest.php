<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Participant;
use App\Models\User;
use App\Models\Conference;
use App\Models\SeminarFee;
use App\Models\Membership;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
    }

    public function test_can_get_existing_pending_payment()
    {
        // Create test data
        $user = User::factory()->create();
        $conference = Conference::factory()->create();
        $membership = Membership::factory()->create();
        $participant = Participant::factory()->create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'membership_id' => $membership->id,
        ]);

        // Create existing pending payment
        $existingPayment = Payment::factory()->create([
            'participant_id' => $participant->id,
            'payment_status' => 'pending',
            'invoice_code' => 'INV-TEST-001',
        ]);

        // Get payment through service
        $payment = $this->paymentService->getOrCreatePayment($participant->id);

        // Should return existing payment, not create new one
        $this->assertEquals($existingPayment->id, $payment->id);
        $this->assertEquals('INV-TEST-001', $payment->invoice_code);
        $this->assertEquals('pending', $payment->payment_status);

        // Verify only one payment exists
        $this->assertEquals(1, Payment::where('participant_id', $participant->id)->count());
    }

    public function test_can_reuse_valid_snap_token()
    {
        $payment = Payment::factory()->create([
            'payment_status' => 'pending',
            'snap_token' => 'test-token-123',
            'snap_token_created_at' => now()->subHours(2), // Valid token (less than 24 hours)
        ]);

        $this->assertTrue($payment->hasValidSnapToken());

        // Should return existing token
        $token = $this->paymentService->getSnapToken($payment);
        $this->assertEquals('test-token-123', $token);
    }

    public function test_expired_token_requires_regeneration()
    {
        $payment = Payment::factory()->create([
            'payment_status' => 'pending',
            'snap_token' => 'expired-token',
            'snap_token_created_at' => now()->subHours(25), // Expired token
        ]);

        $this->assertFalse($payment->hasValidSnapToken());
    }

    public function test_paid_payment_cannot_be_paid_again()
    {
        $payment = Payment::factory()->create([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->assertFalse($payment->canBePaid());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Payment cannot be processed');

        $this->paymentService->getSnapToken($payment);
    }
}
