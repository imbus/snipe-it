<?php

namespace Tests\Feature\Manufacturers\Ui;

use App\Models\User;
use Tests\Concerns\TestsPermissionsRequirement;
use Tests\TestCase;

class BulkDeleteManufacturersTest extends TestCase implements TestsPermissionsRequirement
{
    public function testRequiresPermission()
    {
        $this->actingAs(User::factory()->create())
            ->delete(route('manufacturers.bulk.delete'), [
                'ids' => [1, 2, 3]
            ])
            ->assertForbidden();
    }

    public function test_manufacturer_cannot_be_bulk_deleted_if_models_still_associated()
    {
        //    TODO: implement
    }

    public function test_manufacturers_can_be_bulk_deleted()
    {
        //    TODO: implement
    }

}
