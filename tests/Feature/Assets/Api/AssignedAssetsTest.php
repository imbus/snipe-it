<?php

namespace Tests\Feature\Assets\Api;

use App\Models\Asset;
use App\Models\User;
use Tests\TestCase;

class AssignedAssetsTest extends TestCase
{
    public function test_requires_permission()
    {
        $this->actingAsForApi(User::factory()->create())
            ->getJson(route('api.assets.assigned_assets' , Asset::factory()->create()))
            ->assertForbidden();
    }

    public function test_can_get_assets_assigned_to_specific_asset()
    {
        $this->markTestIncomplete();

        // check out asset to an asset

        // make request

        // assert assigned asset included in response
    }
}
