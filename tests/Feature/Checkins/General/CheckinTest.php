<?php

namespace Tests\Feature\Checkins\General;

use App\Models\Accessory;
use App\Models\User;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    public function test_gracefully_handles_category_being_soft_deleted()
    {
        $this->withoutExceptionHandling();

        $accessory = Accessory::factory()->checkedOutToUser()->create();

        $accessory->category->delete();

        $this->actingAs(User::factory()->checkinAccessories()->create())
            ->post(route('accessories.checkin.store', $accessory->checkouts->first()->id));

        $this->assertEquals(0, $accessory->fresh()->checkouts->count());
    }
}
