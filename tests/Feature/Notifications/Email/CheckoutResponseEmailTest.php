<?php

namespace Tests\Feature\Notifications\Email;

use App\Mail\CheckoutAcceptanceResponseMail;
use App\Models\CheckoutAcceptance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutResponseEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_accepting_checkout_acceptance_configured_to_send_alert()
    {
        $initiator = User::factory()->create();

        $checkoutAcceptance = CheckoutAcceptance::factory()
            ->pending()
            ->withAlertingTo($initiator)
            ->create();

        $this->acceptCheckout($checkoutAcceptance);

        $this->assertEmailSentTo($initiator);
    }

    public function test_declining_checkout_acceptance_configured_to_send_alert()
    {
        $initiator = User::factory()->create();

        $checkoutAcceptance = CheckoutAcceptance::factory()
            ->pending()
            ->withAlertingTo($initiator)
            ->create();

        $this->declineCheckout($checkoutAcceptance);

        $this->assertEmailSentTo($initiator);
    }

    public function test_accepting_checkout_acceptance_not_configured_to_send_alert()
    {
        $initiator = User::factory()->create();

        $checkoutAcceptance = CheckoutAcceptance::factory()
            ->pending()
            ->withoutAlerting()
            ->create();

        $this->acceptCheckout($checkoutAcceptance);

        $this->assertEmailNotSentTo($initiator);
    }

    public function test_declining_checkout_acceptance_not_configured_to_send_alert()
    {
        $initiator = User::factory()->create();

        $checkoutAcceptance = CheckoutAcceptance::factory()
            ->pending()
            ->withoutAlerting()
            ->create();

        $this->declineCheckout($checkoutAcceptance);

        $this->assertEmailNotSentTo($initiator);
    }

    private function assertEmailSentTo(User $user): void
    {
        Mail::assertSent(CheckoutAcceptanceResponseMail::class, function ($mail) use ($user) {
            // @todo: better assertions? accepted vs declined?
            return $mail->hasTo($user->email);
        });
    }

    private function assertEmailNotSentTo(User $user): void
    {
        Mail::assertNotSent(CheckoutAcceptanceResponseMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    private function acceptCheckout(CheckoutAcceptance $checkoutAcceptance): void
    {
        $this->actingAs($checkoutAcceptance->assignedTo)
            ->post(route('account.store-acceptance', $checkoutAcceptance), [
                'asset_acceptance' => 'accepted',
                'note' => null,
            ]);
    }

    private function declineCheckout(CheckoutAcceptance $checkoutAcceptance): void
    {
        $this->actingAs($checkoutAcceptance->assignedTo)
            ->post(route('account.store-acceptance', $checkoutAcceptance), [
                'asset_acceptance' => 'declined',
                'note' => null,
            ]);
    }
}
