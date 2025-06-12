<?php

namespace Tests\Feature\Checkouts\General;

use App\Models\Asset;
use App\Models\Statuslabel;
use App\Models\User;
use Tests\TestCase;

class SettingAlertOnResponseTest extends TestCase
{
    private Asset $asset;
    private User $actor;
    private User $assignedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->asset = Asset::factory()->create();
        $this->actor = User::factory()->checkoutAssets()->create();
        $this->assignedUser = User::factory()->create();
    }

    public function test_sets_alert_on_response_if_enabled_by_category()
    {
        $this->asset->model->category->update([
            'require_acceptance' => true,
            'alert_on_response' => true,
        ]);

        $this->postCheckout($this->asset);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Asset::class,
            'checkoutable_id' => $this->asset->id,
            'assigned_to_id' => $this->assignedUser->id,
            'alert_on_response_id' => $this->actor->id,
        ]);
    }

    public function test_does_not_set_alert_on_response_if_disabled_by_category()
    {
        $this->asset->model->category->update([
            'require_acceptance' => true,
            'alert_on_response' => false,
        ]);

        $this->postCheckout($this->asset);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Asset::class,
            'checkoutable_id' => $this->asset->id,
            'assigned_to_id' => $this->assignedUser->id,
            'alert_on_response_id' => null,
        ]);
    }

    private function postCheckout($item): void
    {
        $this->actingAs($this->actor)
            ->post(route('hardware.checkout.store', $item), [
                'checkout_to_type' => 'user',
                'status_id' => (string) Statuslabel::factory()->readyToDeploy()->create()->id,
                'assigned_user' => $this->assignedUser->id,
            ]);
    }
}
