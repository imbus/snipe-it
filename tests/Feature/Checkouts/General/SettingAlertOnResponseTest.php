<?php

namespace Tests\Feature\Checkouts\General;

use App\Models\Asset;
use App\Models\Statuslabel;
use App\Models\User;
use Tests\TestCase;

class SettingAlertOnResponseTest extends TestCase
{
    public function testSetsAlertOnResponseCorrectly()
    {
        $asset = Asset::factory()->create();
        $asset->model->category->update([
            'require_acceptance' => true,
            'alert_on_response' => true,
        ]);

        $actor = User::factory()->checkoutAssets()->create();
        $assignedUser = User::factory()->create();

        $this->actingAs($actor)
            ->post(route('hardware.checkout.store', $asset), [
                'checkout_to_type' => 'user',
                'status_id' => (string) Statuslabel::factory()->readyToDeploy()->create()->id,
                'assigned_user' => $assignedUser->id,
            ]);

        $this->assertDatabaseHas('checkout_acceptances', [
            'checkoutable_type' => Asset::class,
            'checkoutable_id' => $asset->id,
            'assigned_to_id' => $assignedUser->id,
            'alert_on_response_id' => $actor->id,
        ]);
    }
}
