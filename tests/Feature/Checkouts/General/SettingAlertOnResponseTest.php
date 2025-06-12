<?php

namespace Tests\Feature\Checkouts\General;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Statuslabel;
use App\Models\User;
use Tests\TestCase;

class SettingAlertOnResponseTest extends TestCase
{
    private User $actor;
    private User $assignedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actor = User::factory()->superuser()->create();
        $this->assignedUser = User::factory()->create();
    }

    public function test_sets_alert_on_response_if_enabled_by_category_for_accessory()
    {
        $accessory = Accessory::factory()->create();
        $accessory->category->update([
            'require_acceptance' => true,
            'alert_on_response' => true,
        ]);

        $this->actingAs($this->actor)
            ->post(route('accessories.checkout.store', $accessory), [
                'checkout_to_type' => 'user',
                'status_id' => (string) Statuslabel::factory()->readyToDeploy()->create()->id,
                'assigned_user' => $this->assignedUser->id,
                'checkout_qty' => 1,
            ]);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Accessory::class,
            'checkoutable_id' => $accessory->id,
            'assigned_to_id' => $this->assignedUser->id,
            'alert_on_response_id' => $this->actor->id,
        ]);
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category_for_accessory()
    {
        $this->markTestIncomplete();
    }

    public function test_sets_alert_on_response_if_enabled_by_category_for_asset()
    {
        $asset = Asset::factory()->create();
        $asset->model->category->update([
            'require_acceptance' => true,
            'alert_on_response' => true,
        ]);

        $this->postAssetCheckout($asset);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Asset::class,
            'checkoutable_id' => $asset->id,
            'assigned_to_id' => $this->assignedUser->id,
            'alert_on_response_id' => $this->actor->id,
        ]);
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category_for_asset()
    {
        $asset = Asset::factory()->create();
        $asset->model->category->update([
            'require_acceptance' => true,
            'alert_on_response' => false,
        ]);

        $this->postAssetCheckout($asset);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Asset::class,
            'checkoutable_id' => $asset->id,
            'assigned_to_id' => $this->assignedUser->id,
            'alert_on_response_id' => null,
        ]);
    }

    public function test_sets_alert_on_response_if_enabled_by_category_for_component()
    {
        $this->markTestIncomplete();
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category_for_component()
    {
        $this->markTestIncomplete();
    }

    public function test_sets_alert_on_response_if_enabled_by_category_for_consumable()
    {
        $this->markTestIncomplete();
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category_for_consumable()
    {
        $this->markTestIncomplete();
    }

    public function test_sets_alert_on_response_if_enabled_by_category_for_license()
    {
        $this->markTestIncomplete();
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category_for_license()
    {
        $this->markTestIncomplete();
    }

    private function postAssetCheckout(Asset $asset): void
    {
        $this->actingAs($this->actor)
            ->post(route('hardware.checkout.store', $asset), [
                'checkout_to_type' => 'user',
                'status_id' => (string) Statuslabel::factory()->readyToDeploy()->create()->id,
                'assigned_user' => $this->assignedUser->id,
            ]);
    }
}
