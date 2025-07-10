<?php

namespace Tests\Feature\Categories\Ui;

use Tests\Concerns\TestsPermissionsRequirement;
use Tests\TestCase;

class BulkDeleteCategoriesTest extends TestCase implements TestsPermissionsRequirement
{
    public function testRequiresPermission()
    {
        //    TODO: implement
    }

    public function test_category_cannot_be_bulk_deleted_if_models_still_associated()
    {
        //    TODO: implement
    }

    public function test_category_can_be_bulk_deleted_if_no_models_associated()
    {
        //    TODO: implement
    }


}