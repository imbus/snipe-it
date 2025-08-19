<?php

namespace Tests\Feature\CheckoutAcceptances\Ui;

use App\Models\Accessory;
use App\Models\AccessoryCheckout;
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
        // $this->markTestIncomplete();

        $user = User::factory()->create();

        $this->actingAs(User::factory()->checkoutAccessories()->create());

        // create accessory that requires acceptance
        $accessoryA = Accessory::factory()->requiringAcceptance()->create(['qty' => 4]);
        $accessoryB = Accessory::factory()->requiringAcceptance()->create(['qty' => 4]);

        // check out the accessory to a user with qty of 2 using the legacy behavior: `checkout_acceptances.qty` is null
        $this->post(route('accessories.checkout.store', $accessoryA), [
            'assigned_user' => $user->id,
            'checkout_qty' => 2,
        ]);

        $this->assertEquals(2, AccessoryCheckout::where([
            'accessory_id' => $accessoryA->id,
            'assigned_to' => $user->id,
            'assigned_type' => User::class,
        ])->count());

        $legacyCheckoutAcceptance = CheckoutAcceptance::query()
            ->where([
                'assigned_to_id' => $user->id,
                'qty' => 2,
            ])
            ->whereNull(['accepted_at', 'declined_at'])
            ->whereHasMorph(
                'checkoutable',
                [Accessory::class],
            )
            ->sole();

        $legacyCheckoutAcceptance->qty = null;
        $legacyCheckoutAcceptance->save();

        // check out the accessory to a user with qty of 2 using the new behavior: `checkout_acceptances.qty` is 2
        $this->post(route('accessories.checkout.store', $accessoryB), [
            'assigned_user' => $user->id,
            'checkout_qty' => 2,
        ]);

        $this->assertEquals(2, AccessoryCheckout::where([
            'accessory_id' => $accessoryB->id,
            'assigned_to' => $user->id,
            'assigned_type' => User::class,
        ])->count());

        $originalAccessoryCheckoutCount = AccessoryCheckout::count();

        $checkoutAcceptance = CheckoutAcceptance::query()
            ->where([
                'assigned_to_id' => $user->id,
                'qty' => 2,
            ])
            ->whereNull(['accepted_at', 'declined_at'])
            ->whereHasMorph(
                'checkoutable',
                [Accessory::class],
            )
            ->sole();

        // decline the "legacy" checkout
        $this->actingAs($user);

        $this->post(route('account.store-acceptance', $legacyCheckoutAcceptance), [
            'asset_acceptance' => 'declined',
        ]);

        // decline the checkout
        $this->post(route('account.store-acceptance', $checkoutAcceptance), [
            'asset_acceptance' => 'declined',
        ]);

        // four rows from `accessories_checkout` should be removed
        $this->assertEquals($originalAccessoryCheckoutCount - 4, AccessoryCheckout::count());

        // ensure existing checkouts for the user are not affected.
        // in other words, make sure the removal of rows from `accessories_checkout` is not too eager, especially around legacy behavior.
        // ie...if a user accepted previous accessories then those should not be touched.
    }
}
