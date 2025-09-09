<?php

namespace Tests\Feature\Assets\Api;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AssignedAssetsTest extends TestCase
{
    public function test_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.assets.assigned_assets' , Asset::factory()->create()))
            ->assertForbidden();
    }

    public function test_adheres_to_company_scoping()
    {
        $this->markTestIncomplete();
    }

    public function test_can_get_assets_assigned_to_specific_asset()
    {
        $unassociatedAsset = Asset::factory()->create();

        $asset = Asset::factory()->hasAssignedAssets(2)->create();

        $assetsAssignedToAsset = Asset::where([
            'assigned_to' => $asset->id,
            'assigned_type' => Asset::class,
        ])->get();

        $this->actingAsForApi(User::factory()->viewAssets()->create())
            ->getJson(route('api.assets.assigned_assets', $asset))
            ->assertOk()
            ->dump()
            ->assertResponseContainsInRows($assetsAssignedToAsset, 'serial')
            ->assertResponseDoesNotContainInRows($unassociatedAsset, 'serial')
            ->assertJson(function (AssertableJson $json) {
                $json->etc();
            });
    }
}
