<?php

namespace Tests\Feature\Notifications\Email;

use App\Mail\CheckoutAcceptanceResponseMail;
use App\Models\CheckoutAcceptance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutResponseEmailTest extends TestCase
{
    public static function scenarios()
    {
        yield 'Accepting checkout acceptance configured to send alert' => [];
        yield 'Declining checkout acceptance configured to send alert' => [];
        yield 'Accepting checkout acceptance not configured to send alert' => [];
        yield 'Declining checkout acceptance not configured to send alert' => [];
    }

    public function test_checkout_response_alert()
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
}
