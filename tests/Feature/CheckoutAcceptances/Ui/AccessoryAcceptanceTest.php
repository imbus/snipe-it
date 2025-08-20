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
use PHPUnit\Framework\Attributes\DataProvider;
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

    public static function data()
    {
        yield 'Current behavior' => [
            function () {
                return function (User $assignee) {
                    return CheckoutAcceptance::query()
                        ->where([
                            'assigned_to_id' => $assignee->id,
                            'qty' => 3,
                        ])
                        ->whereNull('accepted_at')
                        ->whereNull('declined_at')
                        ->whereHasMorph(
                            'checkoutable',
                            [Accessory::class],
                        )
                        ->sole();
                };
            }
        ];

        yield 'Previous behavior' => [
            function () {
                return function (User $assignee) {
                    $checkoutAcceptance = CheckoutAcceptance::query()
                        ->where([
                            'assigned_to_id' => $assignee->id,
                            'qty' => 3,
                        ])
                        ->whereNull('accepted_at')
                        ->whereNull('declined_at')
                        ->whereHasMorph(
                            'checkoutable',
                            [Accessory::class],
                        )
                        ->sole();

                    // previous behavior did not set `qty`.
                    $checkoutAcceptance->qty = null;
                    $checkoutAcceptance->save();

                    return $checkoutAcceptance;
                };
            }
        ];

        // @todo:
        // ensure existing checkouts for the user are not affected.
        // in other words, make sure the removal of rows from `accessories_checkout` is not too eager, especially around legacy behavior.
        // ie...if a user accepted previous accessories then those should not be touched.
    }

    /**
     * @link https://github.com/grokability/snipe-it/issues/17589
     */
    #[DataProvider('data')]
    public function test_all_accessory_checkouts_are_removed_when_user_declines_acceptance($provided)
    {
        $getCheckoutAcceptance = $provided();

        $assignee = User::factory()->create();

        $this->actingAs(User::factory()->checkoutAccessories()->create());

        // create accessory that requires acceptance
        $accessory = Accessory::factory()->requiringAcceptance()->create(['qty' => 5]);

        // checkout 3 accessories to the user
        $this->post(route('accessories.checkout.store', $accessory), [
            'assigned_user' => $assignee->id,
            'checkout_qty' => 3,
        ]);

        $originalAccessoryCheckoutCount = AccessoryCheckout::count();

        // get the checkout acceptance via the function that will put it in a state ready for testing
        $checkoutAcceptance = $getCheckoutAcceptance($assignee);

        // decline the checkout
        $this->actingAs($assignee)
            ->post(route('account.store-acceptance', $checkoutAcceptance), [
                'asset_acceptance' => 'declined',
            ]);

        $this->assertEquals($originalAccessoryCheckoutCount - 3, AccessoryCheckout::count());
    }
}
