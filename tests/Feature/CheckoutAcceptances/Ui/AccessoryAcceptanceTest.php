<?php

namespace Tests\Feature\CheckoutAcceptances\Ui;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\CheckoutAcceptance;
use App\Models\User;
use App\Notifications\AcceptanceAssetAcceptedNotification;
use App\Notifications\AcceptanceAssetDeclinedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccessoryAcceptanceTest extends TestCase
{
    /**
     * This can be absorbed into a bigger test
     */
    public function testUsersNameIsIncludedInAccessoryAcceptedNotification()
    {
        Notification::fake();

        $this->settings->enableAlertEmail();

        $acceptance = CheckoutAcceptance::factory()
            ->pending()
            ->for(Accessory::factory()->appleMouse(), 'checkoutable')
            ->create();

        $this->actingAs($acceptance->assignedTo)
            ->post(route('account.store-acceptance', $acceptance), ['asset_acceptance' => 'accepted'])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($acceptance->fresh()->accepted_at);

        Notification::assertSentTo(
            $acceptance,
            function (AcceptanceAssetAcceptedNotification $notification) use ($acceptance) {
                $this->assertStringContainsString(
                    $acceptance->assignedTo->present()->fullName,
                    $notification->toMail()->render()
                );

                return true;
            }
        );
    }

    /**
     * This can be absorbed into a bigger test
     */
    public function testUsersNameIsIncludedInAccessoryDeclinedNotification()
    {
        Notification::fake();

        $this->settings->enableAlertEmail();

        $acceptance = CheckoutAcceptance::factory()
            ->pending()
            ->for(Accessory::factory()->appleMouse(), 'checkoutable')
            ->create();

        $this->actingAs($acceptance->assignedTo)
            ->post(route('account.store-acceptance', $acceptance), ['asset_acceptance' => 'declined'])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($acceptance->fresh()->declined_at);

        Notification::assertSentTo(
            $acceptance,
            function (AcceptanceAssetDeclinedNotification $notification) use ($acceptance) {
                $this->assertStringContainsString(
                    $acceptance->assignedTo->present()->fullName,
                    $notification->toMail($acceptance)->render()
                );

                return true;
            }
        );
    }

    public function testUserIsNotAbleToAcceptAnAssetAssignedToADifferentUser()
    {
        Notification::fake();

        $otherUser = User::factory()->create();

        $acceptance = CheckoutAcceptance::factory()
            ->pending()
            ->for(Asset::factory()->laptopMbp(), 'checkoutable')
            ->create();

        $this->actingAs($otherUser)
            ->post(route('account.store-acceptance', $acceptance), ['asset_acceptance' => 'accepted'])
            ->assertSessionHas(['error' => trans('admin/users/message.error.incorrect_user_accepted')]);

        $this->assertNull($acceptance->fresh()->accepted_at);
    }

    /**
     * @link https://github.com/grokability/snipe-it/issues/17589
     */
    public function test_all_accessory_checkouts_are_removed_when_user_declines_acceptance()
    {
        $this->markTestIncomplete();

        // create accessory that requires acceptance

        // check out the accessory to a user with qty of 2 using the legacy behavior: `checkout_acceptances.qty` is null

        // check out the accessory to a user with qty of 2 using the new behavior: `checkout_acceptances.qty` is 2

        // decline the first checkout
        // ❓

        // decline the second checkout
        // both rows from `accessories_checkout` should be removed

        // ensure existing checkouts for the user are not affected.
        // in other words, make sure the removal of rows from `accessories_checkout` is not too eager, especially around legacy behavior.
    }
}
