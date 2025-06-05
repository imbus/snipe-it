<?php

namespace Tests\Feature\Notifications\Email;

use App\Mail\CheckoutAcceptanceResponseMail;
use App\Models\CheckoutAcceptance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutResponseEmailTest extends TestCase
{
    public function test_accepting_checkout_acceptance_configured_to_send_alert()
    {
        Mail::fake();

        $user = User::factory()->create();

        $checkoutAcceptance = CheckoutAcceptance::factory()
            ->pending()
            ->create([
                'alert_on_response_id' => $user->id,
            ]);

        $this->actingAs($checkoutAcceptance->assignedTo)
            ->post(route('account.store-acceptance', $checkoutAcceptance), [
                'asset_acceptance' => 'accepted',
                'note' => null,
            ]);

        Mail::assertSent(CheckoutAcceptanceResponseMail::class, function ($mail) use ($user) {
            // @todo: better assertions? accepted vs declined?
            return $mail->hasTo($user->email);
        });
    }

    public function test_declining_checkout_acceptance_configured_to_send_alert()
    {
        $this->markTestIncomplete();
    }

    public function test_accepting_checkout_acceptance_not_configured_to_send_alert()
    {
        $this->markTestIncomplete();
    }

    public function test_declining_checkout_acceptance_not_configured_to_send_alert()
    {
        $this->markTestIncomplete();
    }
}
